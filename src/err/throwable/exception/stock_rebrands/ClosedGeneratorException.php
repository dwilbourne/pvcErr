<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\ErrorExceptionMsg;
use Throwable;

/**
 * closed generator exceptions are thrown when one tries to access a generated value where the generator has
 * been closed.
 *
 * From the php documentation:
 *
 * -----------------------------------------------
 * A generator can be closed in two ways:
 *
 * Reaching a return statement (or the end of the function) in a generator or throwing an exception from it
 * (without catching it inside the generator). Removing all references to the generator object. In this case the
 * generator will be closed as part of the garbage collection process.
 *
 * -----------------------------------------------
 *
 * Example:
 *
 *    function counter($start, $stop, $step) {
 *        for($i = $start; $i <= $stop; $i++) {
 *            yield($i);
 *        }
 *        return;
 *    }
 *
 *    $y = counter(4, 9, 1);
 *    foreach($y as $i) {
 *        print_r($i);
 *    }
 *
 *    // generator is now closed
 *    if (!$y::valid()) {
 *        throw new pvc\ClosedGeneratorException($y);
 *    }
 *
 *
 */
class ClosedGeneratorException extends Exception
{
    public function __construct(ErrorExceptionMsg $msg, int $code, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::CLOSED_GENERATOR_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
