<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\Msg;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use Throwable;

/**
 *
 * InvalidAttributeException should be thrown when someone tries to access an invalid attribute within an object
 *
 */
class PregMatchFailureException extends Exception
{
    public function __construct(Msg $msg, int $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::PREG_MATCH_FAILURE_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
