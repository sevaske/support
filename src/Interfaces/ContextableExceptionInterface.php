<?php

namespace Sevaske\Support\Interfaces;

interface ContextableExceptionInterface
{
    /**
     * Attach context information to the exception.
     *
     * @param array $context
     * @return static
     */
    public function withContext(array $context);

    /**
     * Retrieve context information.
     *
     * @return array
     */
    public function context(): array;
}