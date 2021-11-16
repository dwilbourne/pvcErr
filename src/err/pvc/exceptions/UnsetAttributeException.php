<?php declare(strict_types = 1);

namespace pvc\err\pvc\exceptions;

use pvc\err\Exception;
use pvc\err\pvc\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 * UnsetAttributeException should be thrown when someone tries to access an attribute that has not yet been
 * set (and you don't want to return null)
 */
class UnsetAttributeException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::UNSET_ATTRIBUTE_EXCEPTION;
    }
}
