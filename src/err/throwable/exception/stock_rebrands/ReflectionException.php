<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\Msg;
use Throwable;

/**
 * Reflection exceptions are thrown when you try to reflect something that is not reflectable (e.g. not an object)
 *
 *
 * Example:
 *
 *    $r = new ReflectionClass("foo");
 *
 */
class ReflectionException extends Exception
{
    public function __construct(Msg $msg, int $code, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::REFLECTION_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
