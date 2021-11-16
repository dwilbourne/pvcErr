<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;

use pvc\err\stock\messages\InvalidArgumentMsg;
use tests\err\MsgTester;

/**
 * Class InvalidArgumentMsgTest
 * @covers \pvc\err\stock\messages\InvalidArgumentMsg
 */
class InvalidArgumentMsgTest extends MsgTester
{
    public function testInvalidArgumentMsg(): void
    {
        $valueProvided = 'foo';
        $acceptableDataTypes = 'bar, baz quuz';
        $msg = new InvalidArgumentMsg($valueProvided, $acceptableDataTypes);
        $this->runAssertions($msg);
    }
}