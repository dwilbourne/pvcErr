<?php declare(strict_types = 1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvc\err\stock\messages;

use pvc\msg\Msg;

class InvalidDataTypeMsg extends Msg
{
    public function __construct(string $value, string $dataTypeSupplied, string $dataTypeRequired)
    {
        $msgVars = [$value, $dataTypeSupplied, $dataTypeRequired];
        $msgText = 'Value supplied (%s) has an invalid data type <%s>.  Must be of type <%s>.';
        parent::__construct($msgVars, $msgText);
    }
}
