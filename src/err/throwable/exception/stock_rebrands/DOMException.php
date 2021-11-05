<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\Msg;
use Throwable;

/**
 * DOM exceptions are thrown from attempting illegal DOM functions / methods or calling them with bad arguments
 *
 */
class DOMException extends Exception
{
    public function __construct(Msg $msg, int $code, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::DOM_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
