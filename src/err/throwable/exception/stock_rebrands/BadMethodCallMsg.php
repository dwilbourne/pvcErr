<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\msg\Msg;

/**
 * Class OutOfContextMethodCallMsg
 */
class BadMethodCallMsg extends Msg
{
    public function __construct(string $methodName)
    {
        $msgVars = [$methodName];
        $msgText = 'The method (%s) is not defined in this object.';
        parent::__construct($msgVars, $msgText);
    }
}
