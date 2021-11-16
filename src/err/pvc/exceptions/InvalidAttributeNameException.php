<?php declare(strict_types = 1);

namespace pvc\err\pvc\exceptions;

use pvc\err\Exception;
use pvc\err\pvc\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 * Class InvalidAttributeNameException
 *
 * InvalidAttributetypeException should be thrown when someone tries to set an object attribute to a
 * value with in incorrect type
 */
class InvalidAttributeNameException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::INVALID_ATTRIBUTE_NAME_EXCEPTION;
    }
}
