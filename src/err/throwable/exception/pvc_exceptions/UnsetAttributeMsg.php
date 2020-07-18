<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\ErrorExceptionMsg;

/**
 * Class InvalidAttributeExceptionMsg
 */
class UnsetAttributeMsg extends ErrorExceptionMsg
{
    public function __construct(string $attributeName)
    {
        $msgVars = [$attributeName];
        $msgText = 'Attempt to get value of attribute (%s) which which has not been set yet.';
        parent::__construct($msgVars, $msgText);
    }
}
