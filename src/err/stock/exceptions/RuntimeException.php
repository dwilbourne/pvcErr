<?php declare(strict_types = 1);

namespace pvc\err\stock\exceptions;

use pvc\err\Exception;
use pvc\err\stock\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

class RuntimeException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::RUNTIME_EXCEPTION;
    }
}
