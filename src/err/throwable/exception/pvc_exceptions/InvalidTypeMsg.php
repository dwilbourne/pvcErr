<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\ErrorExceptionMsg;
use pvc\err\throwable\exception\stock_rebrands\InvalidArgumentException;

/**
 * Class InvalidTypeMsg
 */
class InvalidTypeMsg extends ErrorExceptionMsg
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
