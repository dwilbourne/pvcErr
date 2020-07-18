<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\msg\ErrorExceptionMsg;

/**
 * Class OutOfContextMethodCallMsg
 */
class ClosedGeneratorMsg extends ErrorExceptionMsg
{
    public function __construct(string $generatorName)
    {
        $msgVars = [$generatorName];
        $msgText = 'The generator (%s) was closed at the time it was referenced.';
        parent::__construct($msgVars, $msgText);
    }
}
