<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use pvc\msg\Msg;
use Throwable;

/**
 * InvalidFilenameException should be thrown when someone tries to specify illegal characters in a filename.
 */

/**
 * Class InvalidFilenameException
 */
class InvalidFilenameException extends Exception
{
    public function __construct(Msg $msg, int $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::INVALID_FILENAME_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
