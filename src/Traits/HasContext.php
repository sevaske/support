<?php

namespace Sevaske\Support\Traits;

trait HasContext
{
    protected array $context = [];

    public function withContext(array $context)
    {
        $this->context = array_merge($this->context, $context);

        return $this;
    }

    public function context(): array
    {
        return $this->context;
    }
}