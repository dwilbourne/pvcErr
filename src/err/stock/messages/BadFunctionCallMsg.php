<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

/**
 * Class BadFunctionCallMsg
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
