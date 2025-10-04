<?php

namespace Sevaske\Support\Exceptions;

use Sevaske\Support\Interfaces\ContextableExceptionInterface;
use Sevaske\Support\Traits\HasContext;

class ContextableException extends \Exception implements ContextableExceptionInterface
{
    use HasContext;

    public function __construct(string $message = '', array $context = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }
}