<?php declare(strict_types = 1);

namespace pvc\err\stock\exceptions;

use pvc\err\Exception;
use pvc\err\stock\ExceptionConstants;
use pvc\msg\Msg;
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
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::DOM_ARGUMENT_EXCEPTION;
    }
}
