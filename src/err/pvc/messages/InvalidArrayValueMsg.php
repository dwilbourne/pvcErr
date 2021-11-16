<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class InvalidArrayValueMsg
 */
class InvalidArrayValueMsg extends Msg
{
    /**
     * InvalidArrayValueMsg constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $msgText = 'Invalid array value (%s).';
        $msgVars = [$value];
        parent::__construct($msgVars, $msgText);
    }
}
