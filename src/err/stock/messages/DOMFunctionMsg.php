<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

/**
 * Class DOMFunctionMsg */
class DOMFunctionMsg extends Msg
{
    public function __construct(string $DOMFunctionName)
    {
        $msgVars = [$DOMFunctionName];
        $msgText = 'The DOM function or method (%s) either does not exist or was called incorrectly.';
        parent::__construct($msgVars, $msgText);
    }
}
