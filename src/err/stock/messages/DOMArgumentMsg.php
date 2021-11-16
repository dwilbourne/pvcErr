<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

/**
 * Class DOMArgumentMsg
 */
class DOMArgumentMsg extends Msg
{
    public function __construct(string $domFunctionArg, string $domFunctionName)
    {
        $msgVars = [$domFunctionArg, $domFunctionName];
        $msgText = 'The argument (%s) to the DOM function / method (%s) is invalid.';
        parent::__construct($msgVars, $msgText);
    }
}
