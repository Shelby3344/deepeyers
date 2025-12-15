<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class DeepSeekException extends Exception
{
    public function __construct(
        string $message = 'DeepSeek API error',
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function isRetryable(): bool
    {
        return in_array($this->code, [408, 429, 500, 502, 503, 504]);
    }

    public function isRateLimited(): bool
    {
        return $this->code === 429;
    }

    public function isUnauthorized(): bool
    {
        return $this->code === 401;
    }
}
