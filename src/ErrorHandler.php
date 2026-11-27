<?php

declare(strict_types=1);

namespace pvc\err;

/**
 * Class ErrorHandler
 */
class ErrorHandler
{
    public const UNDEFINED = 0;
    public const  NOTICE = 1;
    public const  WARNING = 2;
    public const  PARSE = 3;
    public const  FATAL = 4;
    protected int $errorExceptionCode = 0;

    public static function friendlyErrorType(int $type): string
    {
        return match ($type) {
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            /**
             * E_STRICT is deprecated in php 8.4
             */
            // E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            default => '(undefined error type)',
        };
    }

    public static function friendlyErrorSeverity(int $severity): string
    {
        return match ($severity) {
            self::NOTICE => 'NOTICE',
            self::WARNING => 'WARNING',
            self::PARSE => 'PARSE',
            self::FATAL => 'FATAL',
            default => '(undefined error severity)',
        };
    }

    public static function getErrorSeverity(int $errno): int
    {
        return match ($errno) {
            E_NOTICE, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED => self::NOTICE,
            E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING => self::WARNING,
            E_PARSE => self::PARSE,
            E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR => self::FATAL,
            default => self::UNDEFINED,
        };
    }

    public function handle(
        int $errno,
        string $errstr,
        ?string $errfile = null,
        ?int $errline = null,
    ): void {
        throw new ErrorException(
            $errstr,
            $this->errorExceptionCode,
            $errno,
            $errfile,
            $errline
        );
    }
}
