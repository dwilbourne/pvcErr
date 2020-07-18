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
class DOMFunctionMsg extends ErrorExceptionMsg
{
    public function __construct(string $DOMFunctionName)
    {
        $msgVars = [$DOMFunctionName];
        $msgText = 'The DOM function or method (%s) either does not exist or was called incorrectly.';
        parent::__construct($msgVars, $msgText);
    }
}
