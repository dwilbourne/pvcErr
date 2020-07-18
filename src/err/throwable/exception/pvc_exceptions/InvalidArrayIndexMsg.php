<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\ErrorExceptionMsg;

/**
 * Class InvalidValueMsg
 */
class InvalidArrayIndexMsg extends ErrorExceptionMsg
{
    /**
     * InvalidArrayIndexMsg constructor.
     * @param mixed $indexValue
     * @param string $additionalMessage
     */
    public function __construct($indexValue, string $additionalMessage = '')
    {
        $msgVars = [$indexValue, $additionalMessage];
        $msgText = 'Invalid array index value (%s). ' . $additionalMessage;
        parent::__construct($msgVars, $msgText);
    }
}
