<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class InvalidAttributeExceptionMsg
 */
class UnsetAttributeMsg extends Msg
{
    public function __construct(string $attributeName)
    {
        $msgVars = [$attributeName];
        $msgText = 'Attempt to get value of attribute (%s) which which has not been set yet.';
        parent::__construct($msgVars, $msgText);
    }
}
