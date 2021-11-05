<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\Msg;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use pvc\err\throwable\ErrorExceptionConstants as ec;
use Throwable;

/**
 *
 * InvalidArrayIndexException should be thrown when someone tries to access an array element using an invalid index
 *
 */
class InvalidValueException extends Exception
{
    public function __construct(Msg $msg, int $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::INVALID_VALUE_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
