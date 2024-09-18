<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\err\stock\ErrorException;
use UnhandledMatchError;

/**
 * Class ErrorHandler
 */
class ErrorHandler
{
    public const NONE = 0;
    public const  NOTICE = 1;
    public const  WARNING = 2;
    public const  PARSE = 3;
    public const  FATAL = 4;

    protected int $errorSeverity = self::NONE;

    public function friendlyErrorType(int $type): string
    {
        try {
            $type = match ($type) {
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
                E_STRICT => 'E_STRICT',
                E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
                E_DEPRECATED => 'E_DEPRECATED',
                E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            };
        } catch (UnhandledMatchError $e) {
            $type = '(undefined error constant)';
        }
        return $type;
    }

    public function friendlyErrorSeverity(int $severity): string
    {
        try {
            $severity = match ($severity) {
                self::NONE => 'NONE',
                self::NOTICE => 'NOTICE',
                self::WARNING => 'WARNING',
                self::PARSE => 'PARSE',
                self::FATAL => 'FATAL',
            };
        } catch (UnhandledMatchError $e) {
            $severity = '(undefined error severity)';
        }
        return $severity;
    }

    public function getErrorSeverity(): int
    {
        return $this->errorSeverity;
    }

    protected function setErrorSeverity(int $errno): void
    {
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
            case E_STRICT:
                $this->errorSeverity = self::NOTICE;
                break;

            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                $this->errorSeverity = self::WARNING;
                break;

            case E_PARSE:
                $this->errorSeverity = self::PARSE;
                break;

            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $this->errorSeverity = self::FATAL;
                break;
        }
    }

    public function handler(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $this->setErrorSeverity($errno);
        $exceptionCode = 0;
        throw new ErrorException($errstr, $exceptionCode, $errno, $errfile, $errline);
    }
}
