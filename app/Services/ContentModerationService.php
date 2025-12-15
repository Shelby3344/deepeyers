<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AbuseLog;
use Illuminate\Support\Facades\Log;

class ContentModerationService
{
    private array $blockedPatterns;
    private bool $logSuspicious;
    private bool $offensiveMode;

    public function __construct()
    {
        $this->blockedPatterns = config('deepseek.security.blocked_patterns', []);
        $this->logSuspicious = config('deepseek.security.log_suspicious', true);
        $this->offensiveMode = (bool) config('deepseek.security.offensive_mode', true);
    }

    /**
     * Validate user input for malicious content
     * In offensive mode, most restrictions are disabled for pentest scenarios
     */
    public function validateInput(string $content): ValidationResult
    {
        // Check length (always enforced)
        $maxLength = (int) config('deepseek.security.max_input_length', 10000);
        if (strlen($content) > $maxLength) {
            return new ValidationResult(
                valid: false,
                reason: 'Input exceeds maximum length',
                type: 'length_exceeded'
            );
        }

        // In offensive mode, skip most content restrictions
        // This allows pentesters to discuss exploits, payloads, etc.
        if ($this->offensiveMode) {
            // Only check for prompt injection in offensive mode
            if ($this->detectPromptInjection($content)) {
                return new ValidationResult(
                    valid: false,
                    reason: 'Potential prompt injection detected',
                    type: 'prompt_injection'
                );
            }
            return new ValidationResult(valid: true);
        }

        // Standard mode: Check for blocked patterns
        foreach ($this->blockedPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return new ValidationResult(
                    valid: false,
                    reason: 'Content contains blocked patterns',
                    type: 'blocked_pattern'
                );
            }
        }

        // Check for prompt injection attempts
        if ($this->detectPromptInjection($content)) {
            return new ValidationResult(
                valid: false,
                reason: 'Potential prompt injection detected',
                type: 'prompt_injection'
            );
        }

        return new ValidationResult(valid: true);
    }

    /**
     * Detect prompt injection attempts
     * Reduced patterns in offensive mode to allow legitimate pentest discussions
     */
    private function detectPromptInjection(string $content): bool
    {
        $injectionPatterns = [
            '/ignore\s+(all\s+)?(previous|above)\s+instructions/i',
            '/disregard\s+(all\s+)?(previous|above)\s+instructions/i',
            '/forget\s+(all\s+)?(previous|above)\s+instructions/i',
            '/you\s+are\s+now\s+/i',
            '/\[SYSTEM\]/i',
            '/override\s+system/i',
            '/reveal\s+(your|the)\s+(system\s+)?prompt/i',
        ];

        foreach ($injectionPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log suspicious activity
     */
    public function logAbuse(
        string $type,
        string $reason,
        ?int $userId = null,
        ?string $sessionId = null,
        ?string $content = null,
        ?string $ipAddress = null
    ): void {
        if (!$this->logSuspicious) {
            return;
        }

        AbuseLog::log(
            type: $type,
            reason: $reason,
            userId: $userId,
            sessionId: $sessionId,
            content: $this->sanitizeForLog($content),
            ipAddress: $ipAddress,
        );

        Log::warning('Abuse detected', [
            'type' => $type,
            'reason' => $reason,
            'user_id' => $userId,
            'session_id' => $sessionId,
            'ip' => $ipAddress,
        ]);
    }

    /**
     * Sanitize content for logging (avoid storing sensitive data)
     */
    private function sanitizeForLog(?string $content): ?string
    {
        if ($content === null) {
            return null;
        }

        // Truncate long content
        if (strlen($content) > 500) {
            $content = substr($content, 0, 500) . '... [truncated]';
        }

        return $content;
    }
}

/**
 * Validation result DTO
 */
final readonly class ValidationResult
{
    public function __construct(
        public bool $valid,
        public ?string $reason = null,
        public ?string $type = null,
    ) {}

    public function failed(): bool
    {
        return !$this->valid;
    }
}
