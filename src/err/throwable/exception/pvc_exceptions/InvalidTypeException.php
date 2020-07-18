<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\ErrorExceptionMsg;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use pvc\err\throwable\ErrorExceptionConstants as ec;
use Throwable;

/**
 *
 * InvalidArrayIndexException should be thrown when someone tries to access an array element using an invalid index
 *
 */
class InvalidTypeException extends Exception
{
    public function __construct(ErrorExceptionMsg $msg, int $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::INVALID_TYPE_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
