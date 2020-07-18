<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\ErrorExceptionMsg;
use Throwable;

class UnderflowException extends RuntimeException
{
    public function __construct(ErrorExceptionMsg $msg, int $code, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::UNDERFLOW_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
