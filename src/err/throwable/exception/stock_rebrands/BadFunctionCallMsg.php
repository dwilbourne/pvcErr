<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\msg\Msg;

/**
 * Class OutOfContextMethodCallMsg
 */
class BadFunctionCallMsg extends Msg
{
    public function __construct(string $callbackName)
    {
        $msgVars = [$callbackName];
        $msgText = 'The callback (%s) is not defined or the call to the callback had missing / bad arguments.';
        parent::__construct($msgVars, $msgText);
    }
}
