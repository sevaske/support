[![Packagist](https://img.shields.io/packagist/v/sevaske/support.svg?style=flat-square)](https://packagist.org/packages/sevaske/support)
[![PHPUnit](https://github.com/sevaske/support/actions/workflows/tests.yml/badge.svg)](https://github.com/sevaske/support/actions/workflows/tests.yml)
[![PHPStan](https://github.com/sevaske/support/actions/workflows/phpstan.yml/badge.svg)](https://github.com/sevaske/support/actions/workflows/phpstan.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

# PHP Support

Lightweight and minimalistic PHP library providing a set of foundational tools for development.  
Designed to be practical, extendable, and easy to integrate into projects, offering a flexible base for building more complex functionality.


## Installation

```bash
composer require sevaske/support
```


## Features


### Dynamic Attributes (`HasAttributes`)

- Store attributes dynamically with magic methods: `__get`, `__set`, `__isset`, `__unset`.
- Supports array-style access via `ArrayAccess`.
- Utility methods:
    - `fill(array $attributes)` — bulk set attributes.
    - `has(string $key)` — check if an attribute exists.
    - `keys()` — list all attribute keys.
    - `replicate()` — clone object with same attributes.
    - `toArray()`, `jsonSerialize()` — export attributes.

**Example:**

```php
use Sevaske\Support\Traits\HasAttributes;

class User {
    use HasAttributes;
}

$user = new User();
$user->fill(['name' => 'John', 'age' => 30]);
$user->age = 31;
$user->age; // 31
$user['age']; // 31
unset($user->name);

$copy = $user->replicate();
```


### Read-Only Attributes (`HasReadOnlyAttributesContract`)

- Implements a contract to define read-only behavior.
- `readOnlyAttributes` can be `true` (all) or an array of keys.
- Modifying locked attributes throws `LogicException`.

**Example:**
```php
class User implements HasReadOnlyAttributesContract {
    use HasAttributes;

    public function getReadOnlyAttributes(): bool|array 
    {
        return ['age'];
    }
}

$user = new User();
$user->fill(['name' => 'John', 'age' => 30]);
$user->name = 'Alice'; // allowed
$user->age = 32; // throws LogicException
```


### Contextable Exceptions (`HasContext` + `ContextableException`)

- Attach metadata to exceptions.
- Fluent `withContext()` method and `context()` retrieval.

**Example:**
```php
use Sevaske\Support\Exceptions\ContextableException;

    $ex = new ContextableException('Error', ['user_id' => 123]);
    $ex->withContext(['ip' => '127.0.0.1']);
    print_r($ex->context());
```

## Contributing

Contributions are welcome! Feel free to open issues or submit pull requests.  
Please follow PSR-12 coding standards and include tests for any new features or fixes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
