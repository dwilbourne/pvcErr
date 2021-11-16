<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

/**
 * Class BadMethodCallMsg
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
