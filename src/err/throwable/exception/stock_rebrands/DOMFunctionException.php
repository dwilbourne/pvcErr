<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
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
    public function __construct(Msg $msg, int $code, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::DOM_FUNCTION_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
