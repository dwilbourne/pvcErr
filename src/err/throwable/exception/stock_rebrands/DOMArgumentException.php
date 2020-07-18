<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\ErrorExceptionMsg;
use Throwable;

/**
 * DOM exceptions are thrown from attempting illegal DOM functions / methods or calling them with bad arguments
 *
 * Example (using a regex class that is built into pvc):
 *
 *    function createElement($element_name) {
 *        $regex = new RegexXMLElementName();
 *        if (!$regex->Test($element_name)) {
 *            throw new pvc\DOMArgumentException($element_name, "DOMElement::__construct()");
 *        }
 *        return new DOMElement($element_name);
 *    }
 *
 */
class DOMArgumentException extends Exception
{
    public function __construct(ErrorExceptionMsg $msg, int $code, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::DOM_ARGUMENT_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
