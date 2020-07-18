<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use pvc\msg\ErrorExceptionMsg;
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
    public function __construct(ErrorExceptionMsg $msg, int $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::OUT_OF_CONTEXT_METHOD_CALL_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
