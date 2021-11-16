<?php declare(strict_types = 1);

namespace pvc\err\pvc\exceptions;

use pvc\err\Exception;
use pvc\err\pvc\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 * OutOfContextMethodCallException should be thrown when a method is called out of context.
 *
 * This occurs when an object is  because of missing information in the object's state, e.g. one or
 * more attributes are not set. Obviously it is preferable for objects to be created in a valid state and
 * to always be in a valid state, but for complicated objects, that may not be possible.
 *
 * Class OutOfContextMethodCallException
 */
class OutOfContextMethodCallException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::OUT_OF_CONTEXT_METHOD_CALL_EXCEPTION;
    }
}
