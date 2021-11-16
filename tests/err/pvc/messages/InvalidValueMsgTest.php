<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\InvalidValueMsg;
use tests\err\MsgTester;

/**
 * Class InvalidValueMsgTest
 * @covers \pvc\err\pvc\messages\InvalidValueMsg
 */
class InvalidValueMsgTest extends MsgTester
{
    public function testInvalidValueMsg(): void
    {
        $name = 'foo';
        $value = 'bar';
        $additionMessage = 'this is an additional message';
        $msg = new InvalidValueMsg($name, $value, $additionMessage);
        $this->runAssertions($msg);
    }
}
