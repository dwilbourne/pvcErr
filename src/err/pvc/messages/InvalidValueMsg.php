<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class InvalidValueMsg
 */
class InvalidValueMsg extends Msg
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
