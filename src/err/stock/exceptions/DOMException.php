<?php declare(strict_types = 1);

namespace pvc\err\stock\exceptions;

use pvc\err\Exception;
use pvc\err\stock\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 * DOM exceptions are thrown from attempting illegal DOM functions / methods or calling them with bad arguments
 *
 */
class DOMException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::DOM_EXCEPTION;
    }
}
