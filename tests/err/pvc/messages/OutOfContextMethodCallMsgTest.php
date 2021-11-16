<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\OutOfContextMethodCallMsg;
use tests\err\MsgTester;

/**
 * Class OutOfContextMethodCallMsgTest
 * @covers \pvc\err\pvc\messages\OutOfContextMethodCallMsg
 */
class OutOfContextMethodCallMsgTest extends MsgTester
{
    public function testOutOfContextMethodCallMsg(): void
    {
        $objectName = 'foo';
        $methodName = 'bar';
        $additionalMsg = 'this is an additional message';
        $msg = new OutOfContextMethodCallMsg($objectName, $methodName, $additionalMsg);
        $this->runAssertions($msg);
    }
}
