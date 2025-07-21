<?php

namespace App\Domain\Shared\Exceptions;

use Exception;

abstract class DomainException extends Exception
{
    protected array $errors = [];

    public function __construct(string $message, array $errors = [], int $code = 422)
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => $this->errors,
            'domain' => $this->getDomain()
        ], $this->getCode());
    }

    abstract protected function getDomain(): string;
} 