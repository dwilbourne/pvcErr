<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\InvalidTypeMsg;
use tests\err\MsgTester;

/**
 * Class InvalidTypeMsgTest
 * @covers \pvc\err\pvc\messages\InvalidTypeMsg
 */
class InvalidTypeMsgTest extends MsgTester
{
    public function testInvalidTypeMsg(): void
    {
        $name = 'foo';
        $types = ['int', 'string'];
        $msg = new InvalidTypeMsg($name, $types);
        $this->runAssertions($msg);
    }
}
