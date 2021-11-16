<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

/**
 * Class ClosedGeneratorMsg
 */
class ClosedGeneratorMsg extends Msg
{
    public function __construct(string $generatorName)
    {
        $msgVars = [$generatorName];
        $msgText = 'The generator (%s) was closed at the time it was referenced.';
        parent::__construct($msgVars, $msgText);
    }
}
