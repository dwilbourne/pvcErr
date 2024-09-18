<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\stock;

use Throwable;

/**
 * Class ErrorException
 */
class ErrorException extends \ErrorException
{
    /**
     * pvc does not make use of the code parameter when creating ErrorExceptions, it is always set to zero.
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
}
