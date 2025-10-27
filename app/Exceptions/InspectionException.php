<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InspectionException extends Exception
{
    protected array $context = [];
    protected string $errorCode = 'INSPECTION_ERROR';
    protected int $httpStatusCode = 500;

    public function __construct(
        string $message = 'An inspection error occurred',
        string $errorCode = 'INSPECTION_ERROR',
        int $httpStatusCode = 500,
        array $context = [],
        ?Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->errorCode = $errorCode;
        $this->httpStatusCode = $httpStatusCode;
        $this->context = $context;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function report(): void
    {
        Log::error('Inspection Exception', [
            'error_code' => $this->errorCode,
            'message' => $this->getMessage(),
            'context' => $this->context,
            'trace' => $this->getTraceAsString(),
            'user_id' => auth()->id(),
            'guard' => auth()->guard()->name,
        ]);
    }

    public function render(Request $request): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => [
                'code' => $this->errorCode,
                'message' => $this->getMessage(),
                'timestamp' => now()->toISOString(),
            ]
        ];

        // Add context in development
        if (app()->environment('local', 'staging')) {
            $response['error']['context'] = $this->context;
            $response['error']['trace'] = $this->getTraceAsString();
        }

        return response()->json($response, $this->httpStatusCode);
    }

    public static function validationFailed(array $errors, array $context = []): self
    {
        return new self(
            'Validation failed',
            'INSPECTION_VALIDATION_FAILED',
            422,
            array_merge($context, ['validation_errors' => $errors])
        );
    }

    public static function notFound(string $identifier, array $context = []): self
    {
        return new self(
            "Inspection not found: {$identifier}",
            'INSPECTION_NOT_FOUND',
            404,
            array_merge($context, ['identifier' => $identifier])
        );
    }

    public static function unauthorized(string $action, array $context = []): self
    {
        return new self(
            "Unauthorized to perform action: {$action}",
            'INSPECTION_UNAUTHORIZED',
            403,
            array_merge($context, ['action' => $action])
        );
    }

    public static function businessRuleViolation(string $rule, array $context = []): self
    {
        return new self(
            "Business rule violation: {$rule}",
            'INSPECTION_BUSINESS_RULE_VIOLATION',
            422,
            array_merge($context, ['rule' => $rule])
        );
    }

    public static function externalServiceError(string $service, array $context = []): self
    {
        return new self(
            "External service error: {$service}",
            'INSPECTION_EXTERNAL_SERVICE_ERROR',
            502,
            array_merge($context, ['service' => $service])
        );
    }
}
