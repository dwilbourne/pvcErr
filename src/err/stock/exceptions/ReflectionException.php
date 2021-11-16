<?php declare(strict_types = 1);

namespace pvc\err\stock\exceptions;

use pvc\err\Exception;
use pvc\err\stock\ExceptionConstants;
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
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::REFLECTION_EXCEPTION;
    }
}
