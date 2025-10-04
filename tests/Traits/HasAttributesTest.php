<?php

namespace Tests\Traits;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Sevaske\Support\Interfaces\HasAttributesContract;
use Sevaske\Support\Interfaces\HasReadOnlyAttributesContract;
use Sevaske\Support\Traits\HasAttributes;

class HasAttributesTest extends TestCase
{
    /**
     * Create a new object using the HasAttributes trait.
     *
     * @return object
     */
    protected function createObject(): object
    {
        return new class implements HasAttributesContract {
            use HasAttributes;
        };
    }

    protected function createReadonlyObject(array $attributes = [], $readOnly = true): object
    {
        return new class($attributes, $readOnly) implements HasAttributesContract, HasReadOnlyAttributesContract {
            use HasAttributes;

            private $readOnly;

            public function __construct(array $attributes, $readOnly)
            {
                $this->attributes = $attributes;
                $this->readOnly = $readOnly;
            }

            public function getReadOnlyAttributes()
            {
                return $this->readOnly;
            }
        };
    }

    public function testFillAndGetAttribute(): void
    {
        // Create object and fill attributes
        $obj = $this->createObject();
        $obj->fill(['name' => 'John', 'age' => 30]);

        // Test __get and getAttribute / getOptionalAttribute
        $this->assertEquals('John', $obj->name);
        $this->assertEquals(30, $obj->age);
        $this->assertEquals('John', $obj->getAttribute('name'));
        $this->assertNull($obj->getOptionalAttribute('missing'));
    }

    public function testHasAndKeys(): void
    {
        $obj = $this->createObject();
        $obj->fill(['name' => 'John', 'age' => 30]);

        // Test has() method
        $this->assertTrue($obj->has('name'));
        $this->assertFalse($obj->has('missing'));

        // Test keys() method
        $this->assertEquals(['name', 'age'], $obj->keys());
    }

    public function testReplicateCreatesCopy(): void
    {
        $obj = $this->createObject();
        $obj->fill(['name' => 'John']);

        // Create a copy
        $copy = $obj->replicate();

        // The copy should not be the same object
        $this->assertNotSame($obj, $copy);

        // Attributes should be equal
        $this->assertEquals($obj->toArray(), $copy->toArray());

        // Changing copy should not affect original
        $copy->name = 'Alice';
        $this->assertEquals('John', $obj->name);
        $this->assertEquals('Alice', $copy->name);
    }

    public function testMagicSetGetUnset(): void
    {
        $obj = $this->createObject();

        // Set attribute via magic __set
        $obj->name = 'John';
        $this->assertEquals('John', $obj->name);
        $this->assertTrue(isset($obj->name));

        // Unset attribute via magic __unset
        unset($obj->name);
        $this->assertFalse(isset($obj->name));
        $this->assertNull($obj->name);
    }

    public function testReadOnlyTrue(): void
    {
        $obj = $this->createReadonlyObject(['name' => 'John']);

        // Trying to modify should throw LogicException
        $this->expectException(\LogicException::class);
        $obj->name = 'Alice';
    }

    public function testReadOnlyArray(): void
    {
        $obj = $this->createReadonlyObject(['name' => 'John', 'age' => 30], ['age']);

        // Non-read-only attribute should be modifiable
        $obj->name = 'Alice';
        $this->assertEquals('Alice', $obj->name);

        // Modifying read-only attribute should throw
        $this->expectException(\LogicException::class);
        $obj->age = 55;
    }

    public function testArrayAccess(): void
    {
        $obj = $this->createObject();

        // Set and get via ArrayAccess
        $obj['name'] = 'John';
        $this->assertEquals('John', $obj['name']);
        $this->assertTrue(isset($obj['name']));

        // Unset via ArrayAccess
        unset($obj['name']);
        $this->assertFalse(isset($obj['name']));
        $this->assertNull($obj['name']);
    }

    public function testToArrayAndJsonSerialize(): void
    {
        $obj = $this->createObject();
        $obj->fill(['name' => 'John', 'age' => 30]);

        // toArray() should return all attributes
        $this->assertEquals(['name' => 'John', 'age' => 30], $obj->toArray());

        // jsonSerialize() should return same as toArray()
        $this->assertEquals(['name' => 'John', 'age' => 30], $obj->jsonSerialize());
    }

    public function testGetAttributeThrowsException(): void
    {
        $obj = $this->createObject();

        // getAttribute() for undefined key should throw InvalidArgumentException
        $this->expectException(InvalidArgumentException::class);
        $obj->getAttribute('missing');
    }
}
