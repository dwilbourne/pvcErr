<?php declare(strict_types = 1);

namespace pvc\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 * From the PHP manual:
 *
 * Out of Range exceptions should be thrown when an illegal index was requested. This represents errors that
 * "should be detected at compile time."
 *
 * It's unclear how to create a circumstance where the compiler "should know but doesn't" and then catch it in your
 * code.  For example, indexing into a fixed length array with a bad index creates an error, not an exception.
 * If it was a user that supplied the bad index, it might call for sending a message back to the user through the
 * UI, but then you do not have an exception, you have bad user input......
 */

class OutOfRangeException extends LogicException
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::OUT_OF_RANGE_EXCEPTION;
    }
}
