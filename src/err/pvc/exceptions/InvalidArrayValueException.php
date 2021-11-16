<?php declare(strict_types = 1);

namespace pvc\err\pvc\exceptions;

use pvc\err\Exception;
use pvc\err\pvc\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 * InvalidArrayValueException should be thrown when someone tries to access an array element using an invalid index
 */

/**
 * Class InvalidArrayValueException
 */
class InvalidArrayValueException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::INVALID_ARRAY_VALUE_EXCEPTION;
    }
}
