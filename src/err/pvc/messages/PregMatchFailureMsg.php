<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class InvalidPHPVersionMsg
 */
class PregMatchFailureMsg extends Msg
{
    public function __construct(string $regex, string $subject)
    {
        $msgVars = [$regex, $subject];
        $msgText = 'preg_match failed with the following arguments: regex=%s; subject=%s';
        parent::__construct($msgVars, $msgText);
    }
}
