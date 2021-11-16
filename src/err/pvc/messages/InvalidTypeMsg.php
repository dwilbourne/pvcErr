<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\pvc\messages;

use pvc\msg\Msg;

/**
 * Class InvalidTypeMsg
 */
class InvalidTypeMsg extends Msg
{
    /**
     * InvalidTypeMsg constructor.
     * @param string $name
     * @param string[] $allowedTypes
     */
    public function __construct(string $name, array $allowedTypes)
    {
        $c = count($allowedTypes);
        $z = implode(',', array_fill(0, $c, '%s'));

        $msgText = ($c > 1) ? 'one of the following types: ' : 'of the following type: ';
        $msgText = 'variable (%s) must be ' . $msgText . $z;
        $vars = array_merge([$name], $allowedTypes);
        parent::__construct($vars, $msgText);
    }
}
