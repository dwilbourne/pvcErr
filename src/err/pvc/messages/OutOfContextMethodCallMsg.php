<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class OutOfContextMethodCallMsg
 */
class OutOfContextMethodCallMsg extends Msg
{
    public function __construct(string $objectName, string $methodName, string $additionalMessage = '')
    {
        $msgVars = [$objectName, $methodName, $additionalMessage];
        $msgText = '';
        $msgText .= 'Unable to execute method (%s) in object (%s):';
        $msgText .= 'The object must be in a different state before executing this method. ' . $additionalMessage;
        parent::__construct($msgVars, $msgText);
    }
}
