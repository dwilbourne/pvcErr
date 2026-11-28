<?php

declare(strict_types=1);

namespace pvc\err\metadata;

enum RetryPolicy: int
{
    /** Permanent failure (e.g., validation, auth, not found); fail immediately without retrying. */
    case None = 0;

    /** Transient blip (e.g., DB deadlock or brief socket drop); safe to retry right away. */
    case Immediate = 1;

    /** Downstream strain or throttling (e.g., external API 502/429); retry with exponential backoff. */
    case Backoff = 2;

    public function shouldRetry(): bool
    {
        return $this !== self::None;
    }

    /**
     * Suggested delay in seconds before the next attempt (1-indexed).
     */
    public function delaySeconds(int $attempt = 1): int
    {
        return match ($this) {
            self::None, self::Immediate => 0,
            self::Backoff => min(
                300,
                2 ** max(0, $attempt - 1)
            ), // 1s, 2s, 4s, 8s... capped at 5m
        };
    }
}