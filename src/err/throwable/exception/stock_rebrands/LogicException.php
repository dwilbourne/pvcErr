<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\Msg;
use Throwable;

/**
 * From the PHP manual:
 *
 * Exception that represents error in the program logic. This kind of exception should lead directly to a fix
 * in your code.
 *
 *
 */
class LogicException extends Exception
{
    public function __construct(Msg $msg, int $code, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::LOGIC_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
