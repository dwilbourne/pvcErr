<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\msg\Msg;

/**
 * Class InvalidArgumentMsg
 */
class InvalidArgumentMsg extends Msg
{
    public function __construct(string $dataType, string $addtlMsg = '')
    {
        $msgVars = [$dataType, $addtlMsg];
        $msgText = 'Invalid argument: must be of type (%s). ' . $addtlMsg;
        parent::__construct($msgVars, $msgText);
    }
}
