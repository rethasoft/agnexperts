<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionService
{
    /**
     * Execute a database transaction with proper error handling and logging
     */
    public static function execute(callable $callback, ?string $operation = null): mixed
    {
        $operation = $operation ?? 'database_operation';
        
        Log::info("Starting database transaction", [
            'operation' => $operation,
            'user_id' => auth()->id(),
            'guard' => auth()->guard()->name,
        ]);

        try {
            return DB::transaction(function () use ($callback, $operation) {
                $result = $callback();
                
                Log::info("Database transaction completed successfully", [
                    'operation' => $operation,
                    'user_id' => auth()->id(),
                ]);
                
                return $result;
            });
        } catch (Throwable $e) {
            Log::error("Database transaction failed", [
                'operation' => $operation,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Execute multiple operations in a single transaction
     */
    public static function executeMultiple(array $operations, ?string $operation = null): array
    {
        $operation = $operation ?? 'multiple_operations';
        
        return self::execute(function () use ($operations) {
            $results = [];
            
            foreach ($operations as $key => $callback) {
                $results[$key] = $callback();
            }
            
            return $results;
        }, $operation);
    }

    /**
     * Execute with retry logic for transient failures
     */
    public static function executeWithRetry(
        callable $callback,
        int $maxRetries = 3,
        int $delayMs = 1000,
        ?string $operation = null
    ): mixed {
        $operation = $operation ?? 'retry_operation';
        $attempt = 0;
        
        while ($attempt < $maxRetries) {
            try {
                return self::execute($callback, $operation);
            } catch (Throwable $e) {
                $attempt++;
                
                // Check if this is a retryable error
                if (!$e instanceof \Illuminate\Database\QueryException || $attempt >= $maxRetries) {
                    throw $e;
                }
                
                Log::warning("Database operation failed, retrying", [
                    'operation' => $operation,
                    'attempt' => $attempt,
                    'max_retries' => $maxRetries,
                    'error' => $e->getMessage(),
                ]);
                
                usleep($delayMs * 1000); // Convert to microseconds
                $delayMs *= 2; // Exponential backoff
            }
        }
        
        throw new \Exception("Operation failed after {$maxRetries} attempts");
    }

    /**
     * Execute with rollback on specific conditions
     */
    public static function executeWithConditionalRollback(
        callable $callback,
        callable $shouldRollback,
        ?string $operation = null
    ): mixed {
        $operation = $operation ?? 'conditional_rollback_operation';
        
        return DB::transaction(function () use ($callback, $shouldRollback, $operation) {
            $result = $callback();
            
            if ($shouldRollback($result)) {
                Log::info("Conditional rollback triggered", [
                    'operation' => $operation,
                    'user_id' => auth()->id(),
                ]);
                
                throw new \Exception("Conditional rollback triggered");
            }
            
            return $result;
        });
    }
}
