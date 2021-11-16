<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\InvalidArrayIndexMsg;
use tests\err\MsgTester;

/**
 * Class InvalidArrayIndexMsgTest
 * @covers \pvc\err\pvc\messages\InvalidArrayIndexMsg
 */
class InvalidArrayIndexMsgTest extends MsgTester
{

    public function testInvalidArrayIndexMsg(): void
    {
        $indexValue = 'foo';
        $msg = new InvalidArrayIndexMsg($indexValue);
        $this->runAssertions($msg);
    }

}
