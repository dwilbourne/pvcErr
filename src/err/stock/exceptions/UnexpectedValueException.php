<?php declare(strict_types = 1);

namespace pvc\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

class UnexpectedValueException extends RuntimeException
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::UNEXPECTED_VALUE_EXCEPTION;
    }
}
