<?php

namespace Sevaske\Support\Interfaces;

interface HasReadOnlyAttributesContract
{
    /**
     * Return read-only configuration.
     *
     * Can be:
     * - true: all attributes are read-only
     * - false: no attributes are read-only
     * - array: list of attribute keys that are read-only
     *
     * @return bool|array
     */
    public function getReadOnlyAttributes();
}