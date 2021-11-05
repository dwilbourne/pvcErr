<?php declare(strict_types = 1);

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\Msg;
use Throwable;

/**
 * Intl exceptions are thrown from objects or functions within the Intl library (which typically needs to be manually
 * uncommented in the php.ini configuration file).  This class is pretty generic and you will probably want to
 * subclass it for more specific exceptions.
 *
 * Example:
 *
 *    function formatNumber($value, ?string $locale = 'en_US', ?int $style = NumberFormatter::DECIMAL) {
 *        $formatter = new NumberFormatter($locale, $style);
 *        // Attempt format.
 *        return $formatter->format($value);
 *    }
 *
 *    try {
 *        formatNumber(123.456, 'en_US', 24601); // bad style
 *    }
 *    catch (\IntlException $e) {
 *        $msg = "Unable to format number with the parameters specified.";
 *        $code = INTL_EXCEPTION;
 *        throw new IntlException($msg, $code);
 *    }
 *
 */
class IntlException extends Exception
{
    public function __construct(Msg $msg, int $code, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ec::INTL_EXCEPTION;
        }
        parent::__construct($msg, $code, $previous);
    }
}
