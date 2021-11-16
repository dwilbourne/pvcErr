<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;

use pvc\err\stock\messages\BadFunctionCallMsg;
use tests\err\MsgTester;

/**
 * Class BadFunctionCallMsgTest
 * @covers \pvc\err\stock\messages\BadFunctionCallMsg
 */
class BadFunctionCallMsgTest extends MsgTester
{
    public function testBadFunctionCallMsg(): void
    {
        $callbackName = 'foo';
        $msg = new BadFunctionCallMsg($callbackName);
        $this->runAssertions($msg);
    }
}
