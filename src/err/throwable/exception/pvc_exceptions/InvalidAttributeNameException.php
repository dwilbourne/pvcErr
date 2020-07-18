<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use pvc\msg\ErrorExceptionMsg;
use Throwable;

/**
 */

/**
 * Class InvalidAttributeNameException
 *
 * InvalidAttributetypeException should be thrown when someone tries to set an object attribute to a
 * value with in incorrect type
 */
class InvalidAttributeNameException extends Exception
{
    public function __construct(ErrorExceptionMsg $msg, int $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::INVALID_ATTRIBUTE_NAME_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
