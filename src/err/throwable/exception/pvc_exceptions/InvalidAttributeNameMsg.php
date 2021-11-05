<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\Msg;

/**
 * Class InvalidAttributeExceptionMsg
 */
class InvalidAttributeNameMsg extends Msg
{
    public function __construct(string $attributeName)
    {
        $msgVars = [$attributeName];
        $msgText = 'Attempt to access an attribute (%s) which does not exist or is not directly accessible.';
        parent::__construct($msgVars, $msgText);
    }
}
