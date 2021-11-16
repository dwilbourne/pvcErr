<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;


use pvc\err\stock\messages\BadMethodCallMsg;
use tests\err\MsgTester;

/**
 * Class BadMethodCallMsgTest
 * @covers \pvc\err\stock\messages\BadMethodCallMsg
 */
class BadMethodCallMsgTest extends MsgTester
{
    public function testBadMethodCallMsg(): void
    {
        $methodName = 'foo';
        $msg = new BadMethodCallMsg($methodName);
        $this->runAssertions($msg);
    }
}