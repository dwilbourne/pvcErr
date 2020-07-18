<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\msg\ErrorExceptionMsg;

/**
 * Class OutOfContextMethodCallMsg
 */
class DOMArgumentMsg extends ErrorExceptionMsg
{
    public function __construct(string $domainArg, string $domainFunctionName)
    {
        $msgVars = [$domainArg, $domainFunctionName];
        $msgText = 'The argument (%s) to the DOM function / method (%s) is invalid.';
        parent::__construct($msgVars, $msgText);
    }
}
