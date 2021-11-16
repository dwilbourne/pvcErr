<?php declare(strict_types = 1);

namespace pvc\err\stock\exceptions;

use pvc\err\Exception;
use pvc\err\stock\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 * From the PHP manual:
 *
 * Exception that represents error in the program logic. This kind of exception should lead directly to a fix
 * in your code.
 */
class LogicException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::LOGIC_EXCEPTION;
    }
}
