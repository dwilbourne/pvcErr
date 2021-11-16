<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\InvalidArrayValueMsg;
use tests\err\MsgTester;

/**
 * Class InvalidArrayValueMsgTest
 * @covers \pvc\err\pvc\messages\InvalidArrayValueMsg
 */
class InvalidArrayValueMsgTest extends MsgTester
{
    public function testInvalidArrayValueMsg(): void
    {
        $arrayValue = 'foo';
        $msg = new InvalidArrayValueMsg($arrayValue);
        $this->runAssertions($msg);
    }
}
