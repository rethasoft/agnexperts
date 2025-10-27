<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $httpCode = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $httpCode);
    }

    public static function created(
        mixed $data = null,
        string $message = 'Resource created successfully',
        array $meta = []
    ): JsonResponse {
        return self::success($data, $message, Response::HTTP_CREATED, $meta);
    }

    public static function updated(
        mixed $data = null,
        string $message = 'Resource updated successfully',
        array $meta = []
    ): JsonResponse {
        return self::success($data, $message, Response::HTTP_OK, $meta);
    }

    public static function deleted(
        string $message = 'Resource deleted successfully',
        array $meta = []
    ): JsonResponse {
        return self::success(null, $message, Response::HTTP_OK, $meta);
    }

    public static function error(
        string $message = 'An error occurred',
        string $errorCode = 'GENERAL_ERROR',
        int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $errors = [],
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'error' => [
                'code' => $errorCode,
                'message' => $message,
                'timestamp' => now()->toISOString(),
            ]
        ];

        if (!empty($errors)) {
            $response['error']['details'] = $errors;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        // Add debug info in development
        if (app()->environment('local', 'staging')) {
            $response['debug'] = [
                'environment' => app()->environment(),
                'version' => app()->version(),
            ];
        }

        return response()->json($response, $httpCode);
    }

    public static function validationError(
        array $errors,
        string $message = 'Validation failed',
        array $meta = []
    ): JsonResponse {
        return self::error(
            $message,
            'VALIDATION_ERROR',
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $errors,
            $meta
        );
    }

    public static function notFound(
        string $message = 'Resource not found',
        string $errorCode = 'NOT_FOUND',
        array $meta = []
    ): JsonResponse {
        return self::error($message, $errorCode, Response::HTTP_NOT_FOUND, [], $meta);
    }

    public static function unauthorized(
        string $message = 'Unauthorized access',
        string $errorCode = 'UNAUTHORIZED',
        array $meta = []
    ): JsonResponse {
        return self::error($message, $errorCode, Response::HTTP_UNAUTHORIZED, [], $meta);
    }

    public static function forbidden(
        string $message = 'Forbidden access',
        string $errorCode = 'FORBIDDEN',
        array $meta = []
    ): JsonResponse {
        return self::error($message, $errorCode, Response::HTTP_FORBIDDEN, [], $meta);
    }

    public static function tooManyRequests(
        string $message = 'Too many requests',
        string $errorCode = 'TOO_MANY_REQUESTS',
        array $meta = []
    ): JsonResponse {
        return self::error($message, $errorCode, Response::HTTP_TOO_MANY_REQUESTS, [], $meta);
    }

    public static function serverError(
        string $message = 'Internal server error',
        string $errorCode = 'SERVER_ERROR',
        array $meta = []
    ): JsonResponse {
        return self::error($message, $errorCode, Response::HTTP_INTERNAL_SERVER_ERROR, [], $meta);
    }

    public static function paginated(
        mixed $data,
        int $currentPage,
        int $perPage,
        int $total,
        int $lastPage,
        string $message = 'Success',
        array $meta = []
    ): JsonResponse {
        $pagination = [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => $lastPage,
            'from' => ($currentPage - 1) * $perPage + 1,
            'to' => min($currentPage * $perPage, $total),
        ];

        return self::success($data, $message, Response::HTTP_OK, array_merge($meta, [
            'pagination' => $pagination
        ]));
    }
}
