<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\ErrorExceptionMsg;

/**
 * Class InvalidPHPVersionMsg
 */
class PregMatchFailureMsg extends ErrorExceptionMsg
{
    public function __construct(string $regex, string $subject)
    {
        $msgVars = [$regex, $subject];
        $msgText = 'preg_match failed with the following arguments: regex=%s; subject=%s';
        parent::__construct($msgVars, $msgText);
    }
}
