<?php declare(strict_types = 1);

namespace pvc\err\stock\exceptions;

use pvc\err\Exception;
use pvc\err\stock\ExceptionConstants;
use pvc\msg\Msg;
use Throwable;

/**
 * DOM exceptions are thrown from attempting illegal DOM functions / methods or calling them with bad arguments
 *
 *    function DomMethodCall($method_name) {
 *        try {
 *            $doc = new DOMDocument('1.0', 'utf-8');
 *            try {
 *                return $doc->$method_name();
 *            }
 *            catch (\DOMException) {
 *                throw new pvc\DOMFunctionException($method_name);
 *            }
 *
 *        }
 *        return new DOMElement($elementName);
 *    }
 */
class DOMFunctionException extends Exception
{
    public function __construct(Msg $msg, Throwable $previous = null)
    {
        parent::__construct($msg, $previous);
        $this->code = ExceptionConstants::DOM_FUNCTION_EXCEPTION;
    }
}
