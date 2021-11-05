<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\Msg;

/**
 * Class InvalidArrayValueMsg
 */
class InvalidArrayValueMsg extends Msg
{
    /**
     * InvalidArrayValueMsg constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        $msgText = 'Invalid array value (%s).';
        $msgVars = [$value];
        parent::__construct($msgVars, $msgText);
    }
}
