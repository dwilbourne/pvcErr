<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\ErrorExceptionMsg;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use pvc\err\throwable\ErrorExceptionConstants as ec;
use Throwable;

/**
 *
 * UnsetAttributeException should be thrown when someone tries to access an attribute that has not yet been
 * set (and you don't want to return null)
 *
 */
class UnsetAttributeException extends Exception
{
    public function __construct(ErrorExceptionMsg $msg, int $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::UNSET_ATTRIBUTE_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
