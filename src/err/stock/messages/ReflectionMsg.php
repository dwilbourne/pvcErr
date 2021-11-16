<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

/**
 * Class ReflectionMsg
 */
class ReflectionMsg extends Msg
{
    public function __construct(string $badClassName)
    {
        $msgVars = [$badClassName];
        $msgText = 'Exception created trying to reflect an object where the classname does not exist (%s).';
        parent::__construct($msgVars, $msgText);
    }
}
