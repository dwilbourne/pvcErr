<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\err\stock\ErrorException;

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
        switch ($type) {
            case E_ERROR: // 1 //
                return 'E_ERROR';
            case E_WARNING: // 2 //
                return 'E_WARNING';
            case E_PARSE: // 4 //
                return 'E_PARSE';
            case E_NOTICE: // 8 //
                return 'E_NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'E_CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR: // 64 //
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING: // 128 //
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'E_USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'E_USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'E_USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192 //
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'E_USER_DEPRECATED';
        }
        return '';
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
