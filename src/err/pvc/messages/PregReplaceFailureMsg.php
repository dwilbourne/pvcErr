<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class InvalidPHPVersionMsg
 */
class PregReplaceFailureMsg extends Msg
{
    public function __construct(string $regex, string $subject, string $replacement)
    {
        $msgVars = [$regex, $subject, $replacement];
        $msgText = 'preg_replace failed with the following arguments: regex=%s; subject=%s; replacement=%s';
        parent::__construct($msgVars, $msgText);
    }
}
