<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;


use pvc\err\stock\messages\InvalidDataTypeMsg;
use tests\err\MsgTester;

/**
 * Class InvalidDataTypeMsgTest
 * @covers \pvc\err\stock\messages\InvalidDataTypeMsg
 */
class InvalidDataTypeMsgTest extends MsgTester
{
    public function testInvalidDataTypeMsg(): void
    {
        $value = 'foo';
        $dataTypeSupplied = 'bar';
        $dataTypeRequired = 'baz, quuz';
        $msg = new InvalidDataTypeMsg($value, $dataTypeSupplied, $dataTypeRequired);
        $this->runAssertions($msg);
    }
}