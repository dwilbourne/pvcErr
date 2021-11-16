<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

/**
 * Class DomainExceptionMsg
 */
class DomainExceptionMsg extends Msg
{
    public function __construct(string $value)
    {
        $msgVars = [$value];
        $msgText = 'Value provided (%s) does not adhere to a defined valid data domain.';
        parent::__construct($msgVars, $msgText);
    }
}