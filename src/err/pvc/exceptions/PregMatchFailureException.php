<?php declare(strict_types = 1);

namespace pvc\err\pvc\exceptions;

use pvc\err\Exception;
use pvc\err\pvc\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 *
 * InvalidAttributeException should be thrown when someone tries to access an invalid attribute within an object
 *
 */
class PregMatchFailureException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::PREG_MATCH_FAILURE_EXCEPTION;
    }
}
