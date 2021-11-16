<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\stock\messages;


use pvc\msg\Msg;

/**
 * Class OutOfBoundsMsg
 */
class OutOfBoundsMsg extends Msg
{
    public function __construct(string $indexSupplied)
    {
        $msgVars = [$indexSupplied];
        $msgText = 'Index into array (%s) is out of bounds, no such index exists.';
        parent::__construct($msgVars, $msgText);
    }
}