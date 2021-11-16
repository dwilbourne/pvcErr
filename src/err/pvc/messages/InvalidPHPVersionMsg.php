<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class InvalidPHPVersionMsg
 */
class InvalidPHPVersionMsg extends Msg
{
    /**
     * InvalidPHPVersionMsg constructor.
     * @param string $minPHPVersion
     */
    public function __construct(string $minPHPVersion)
    {
        $msgVars = [$minPHPVersion];
        $msgText = 'PHP version must be at least %s';
        parent::__construct($msgVars, $msgText);
    }
}
