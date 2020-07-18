<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\msg\ErrorExceptionMsg;

class InvalidDataTypeMsg extends ErrorExceptionMsg
{
    public function __construct(string $dataType)
    {
        $msgVars = [$dataType];
        $msgText = 'Invalid data type (%s)';
        parent::__construct($msgVars, $msgText);
    }
}
