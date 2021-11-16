<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;

use pvc\err\stock\messages\ReflectionMsg;
use tests\err\MsgTester;

/**
 * Class ReflectionMsgTest
 * @covers \pvc\err\stock\messages\ReflectionMsg
 */
class ReflectionMsgTest extends MsgTester
{
    public function testReflectionMsg(): void
    {
        $badClassName = 'foo';
        $msg = new ReflectionMsg($badClassName);
        $this->runAssertions($msg);
    }
}