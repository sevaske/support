<?php

namespace Sevaske\Support\Traits;

use InvalidArgumentException;

trait HasAttributes
{
    /**
     * Internal storage for dynamic attributes.
     */
    protected array $attributes = [];

    /**
     * If true, attributes cannot be modified.
     * Can also be an array of keys that are read-only.
     *
     * @var bool|array
     */
    protected $readOnlyAttributes = false;

    /**
     * Fill multiple attributes at once.
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->__set($key, $value);
        }

        return $this;
    }

    /**
     * Magic method to retrieve the value of a dynamic attribute.
     *
     * @param  string  $name  The name of the attribute.
     * @return mixed|null The value of the attribute or null if not set.
     */
    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Magic method to set the value of a dynamic attribute.
     *
     * @param  string  $name  The name of the attribute.
     * @param  mixed  $value  The value to assign to the attribute.
     */
    public function __set(string $name, $value)
    {
        if ($this->isReadOnly($name)) {
            throw new \LogicException("Cannot modify read-only attribute: {$name}");
        }

        $this->attributes[$name] = $value;
    }

    /**
     * Magic method to check if a dynamic attribute is set.
     *
     * @param  string  $name  The name of the attribute.
     * @return bool True if the attribute is set, false otherwise.
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Magic method to unset a dynamic attribute.
     *
     * @param  string  $name  The name of the attribute to unset.
     */
    public function __unset(string $name): void
    {
        if ($this->isReadOnly($name)) {
            throw new \LogicException("Cannot unset read-only attribute: {$name}");
        }

        unset($this->attributes[$name]);
    }

    /**
     * Retrieves an attribute value.
     *
     * @param  string  $key  The attribute key.
     * @return mixed The attribute value.
     *
     * @throws InvalidArgumentException
     */
    public function getAttribute(string $key)
    {
        if (! isset($this->attributes[$key])) {
            throw new InvalidArgumentException('Undefined attribute: '.$key);
        }

        return $this->attributes[$key];
    }

    /**
     * Retrieves an attribute value or null if undefined.
     *
     * @param  string  $key  The attribute key.
     * @return mixed|null The attribute value or null if not found.
     */
    public function getOptionalAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Checks whether the given offset exists in the internal attributes.
     *
     * @param  mixed  $offset  The attribute key.
     * @return bool True if set, false otherwise.
     */
    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Retrieves a value by array key (offset).
     *
     * @param  mixed  $offset  The attribute key.
     * @return mixed|null The attribute value, or null if not set.
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset] ?? null;
    }

    /**
     * Sets a value by array key (offset).
     *
     * @param  mixed  $offset  The attribute key.
     * @param  mixed  $value  The value to set.
     */
    public function offsetSet($offset, $value): void
    {
        if ($this->isReadOnly($offset)) {
            throw new \LogicException("Cannot modify read-only attribute: {$offset}");
        }

        $this->attributes[$offset] = $value;
    }

    /**
     * Unsets a value by array key (offset).
     *
     * @param  mixed  $offset  The attribute key.
     */
    public function offsetUnset($offset): void
    {
        if ($this->isReadOnly($offset)) {
            throw new \LogicException("Cannot unset read-only attribute: {$offset}");
        }

        unset($this->attributes[$offset]);
    }

    /**
     * Return all attributes as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Serializes the internal attributes to an array for JSON representation.
     *
     * @return array The internal attributes.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Determines if an attribute is read-only.
     *
     * @param string $key
     * @param bool $strict
     * @return bool
     */
    protected function isReadOnly(string $key, bool $strict = true): bool
    {
        if ($this->readOnlyAttributes === true) {
            return true;
        }

        if (is_array($this->readOnlyAttributes)) {
            return in_array($key, $this->readOnlyAttributes, $strict);
        }

        return false;
    }
}
