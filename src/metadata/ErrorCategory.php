<?php

declare(strict_types=1);

namespace pvc\err\metadata;

use Psr\Log\LogLevel;

enum ErrorCategory: int
{
    case Authentication = 1;
    case Authorization = 2;
    case Conflict = 3;
    case DomainLogic = 4;
    case ExternalService = 5;
    case NotFound = 6;
    case RateLimit = 7;

    /** Internal runtime bugs as well as infrastructure/I/O failures (database, filesystem, cache, queues). */
    case System = 8;
    case Validation = 9;

    /**
     * Maps each category to a standard PSR-3 log level (Psr\Log\LogLevel).
     *
     * @return LogLevel::*
     */
    public function logLevel(): string
    {
        return match ($this) {
            // Routine client mistakes: log for debugging/audit without triggering alerts
            self::Validation,
            self::NotFound,
            self::DomainLogic => LogLevel::INFO,

            // Expected friction or security/traffic signals worth monitoring for spikes
            self::Authentication,
            self::Authorization,
            self::Conflict,
            self::RateLimit => LogLevel::WARNING,

            // Actionable technical or dependency failures that require investigation/alerts
            self::ExternalService,
            self::System => LogLevel::ERROR,
        };
    }

    public function isClientError(): bool
    {
        return $this->httpStatusCode() < 500;
    }

    public function httpStatusCode(): int
    {
        return match ($this) {
            self::Validation, self::DomainLogic => 422, // or 400
            self::Authentication => 401,
            self::Authorization => 403,
            self::NotFound => 404,
            self::Conflict => 409,
            self::RateLimit => 429,
            self::System => 500,
            self::ExternalService => 502,
        };
    }

    public function isSafeToExposeMessage(): bool
    {
        return match ($this) {
            self::System, self::ExternalService => false,
            default => true,
        };
    }

    public function isRetryable(): bool
    {
        return match ($this) {
            self::ExternalService, self::RateLimit => true,
            default => false,
        };
    }

    public function slug(): string
    {
        return match ($this) {
            self::Authentication => 'authentication_error',
            self::Authorization => 'authorization_error',
            self::Conflict => 'conflict',
            self::DomainLogic => 'domain_rule_violation',
            self::ExternalService => 'external_service_error',
            self::NotFound => 'not_found',
            self::RateLimit => 'rate_limit_exceeded',
            self::System => 'system_error',
            self::Validation => 'validation_error',
        };
    }

    public function defaultRetryPolicy(): RetryPolicy
    {
        return match ($this) {
            self::ExternalService, self::RateLimit => RetryPolicy::Backoff,
            default => RetryPolicy::None,
        };
    }
}