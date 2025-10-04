<?php

namespace Sevaske\Support\Interfaces;

use ArrayAccess;
use JsonSerializable;

interface HasAttributesContract extends ArrayAccess, JsonSerializable
{
    /**
     * Fill multiple attributes at once.
     *
     * @param array $attributes
     * @return self
     */
    public function fill(array $attributes);

    /**
     * Check if attribute exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Return all attribute keys.
     *
     * @return string[]
     */
    public function keys(): array;

    /**
     * Get attribute value or throw exception if undefined.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key);

    /**
     * Get attribute value or null if undefined.
     *
     * @param string $key
     * @return mixed|null
     */
    public function getOptionalAttribute(string $key);

    /**
     * Return all attributes as array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Replicate the current object with the same attributes.
     *
     * @return self
     */
    public function replicate();
}
