<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;

use pvc\err\stock\messages\OutOfBoundsMsg;
use tests\err\MsgTester;

/**
 * Class OutOfBoundMsgTest
 * @covers \pvc\err\stock\messages\OutOfBoundsMsg
 */
class OutOfBoundMsgTest extends MsgTester
{
    public function testOutOfBoundsMsg(): void
    {
        $indexSupplied = 'foo';
        $msg = new OutOfBoundsMsg($indexSupplied);
        $this->runAssertions($msg);
    }
}