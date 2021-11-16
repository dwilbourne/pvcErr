<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\PregMatchFailureMsg;
use tests\err\MsgTester;

/**
 * Class PregMatchFailureMsgTest
 * @covers \pvc\err\pvc\messages\PregMatchFailureMsg
 */
class PregMatchFailureMsgTest extends MsgTester
{
    public function testPregMatchFailureMsg(): void
    {
        $regex = '/abc/';
        $subject = 'xyz';
        $msg = new PregMatchFailureMsg($regex, $subject);
        $this->runAssertions($msg);
    }
}
