<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class InvalidValueMsg
 */
class InvalidArrayIndexMsg extends Msg
{
    /**
     * InvalidArrayIndexMsg constructor.
     * @param string $indexValue
     */
    public function __construct(string $indexValue)
    {
        $msgVars = [$indexValue];
        $msgText = 'Invalid array index value (%s).';
        parent::__construct($msgVars, $msgText);
    }
}
