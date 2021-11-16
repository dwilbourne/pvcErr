<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

/**
 * Class InvalidArgumentMsg
 */
class InvalidArgumentMsg extends Msg
{
    public function __construct(string $data, string $dataTypes)
    {
        $msgVars = [$data, $dataTypes];
        $msgText = 'Invalid argument supplied (%s): must be one of the following types <%s>.';
        parent::__construct($msgVars, $msgText);
    }
}
