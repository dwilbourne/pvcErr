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
class InvalidValueMsg extends ErrorExceptionMsg
{
    /**
     * InvalidValueMsg constructor.
     * @param string $name
     * @param mixed $value
     * @param string $additionalMsg
     */
    public function __construct(string $name, $value, string $additionalMsg = '')
    {
        $msgVars = [$name, $value, $additionalMsg];
        $msgText = '%s has an invalid value (%s). ' . $additionalMsg;
        parent::__construct($msgVars, $msgText);
    }
}
