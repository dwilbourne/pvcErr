<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use Throwable;

/**
 * Class ErrorException
 */
class ErrorException extends \ErrorException
{
    /**
     * As I understand it, 'severity' in the parent constructor is not actually the severity of
     * the error type, it is the error type itself.  For example, the default of E_ERROR is not
     * a severity (notice / warning / etc.)  It is the error type.  I have maintained the name
     * of the dummy parameter to maintain consistency between this class and the parent class.
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        int $severity = E_ERROR,
        ?string $filename = null,
        ?int $line = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $severity, $filename, $line, $previous);
    }

    /**
     * @return string
     * returns 'NOTICE', 'WARNING', 'PARSE', 'FATAL' (or undefined)
     */
    public function getFriendlySeverity(): string
    {
        /**
         * confusing, but it's not my fault........
         */
        $errorType = $this->getSeverity();
        $severity = ErrorHandler::getErrorSeverity($errorType);
        return ErrorHandler::friendlyErrorSeverity($severity);
    }

    /**
     * @return string
     * converts the error constant to a string
     */
    public function getFriendlyType(): string
    {
        return ErrorHandler::friendlyErrorType($this->getSeverity());
    }
}
