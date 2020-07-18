<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\ErrorExceptionMsg;

/**
 * Class OutOfContextMethodCallMsg
 */
class OutOfContextMethodCallMsg extends ErrorExceptionMsg
{
    public function __construct(string $objectName, string $methodName, string $additionalMessage)
    {
        $msgVars = [$objectName, $methodName, $additionalMessage];
        $msgText = '';
        $msgText .= 'Unable to execute method (%s) in object (%s):';
        $msgText .= 'The object must be in a different state before executing this method. ' . $additionalMessage;

        parent::__construct($msgVars, $msgText);
    }
}
