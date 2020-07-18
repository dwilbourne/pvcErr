<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\ErrorExceptionMsg;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use Throwable;

/**
 * InvalidAttributeException should be thrown when someone tries to access an invalid attribute within an object
 */

/**
 * Class PregReplaceFailureException
 */
class PregReplaceFailureException extends Exception
{
    public function __construct(ErrorExceptionMsg $msg, int $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::PREG_REPLACE_FAILURE_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
