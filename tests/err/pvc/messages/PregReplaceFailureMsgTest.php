<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\PregReplaceFailureMsg;
use tests\err\MsgTester;

/**
 * Class PregReplaceFailureMsgTest
 * @covers \pvc\err\pvc\messages\PregReplaceFailureMsg
 */
class PregReplaceFailureMsgTest extends MsgTester
{
    public function testPregReplaceFailureMsg(): void
    {
        $regex = '/abc/';
        $subject = 'xyz';
        $replace = '123';
        $msg = new PregReplaceFailureMsg($regex, $subject, $replace);
        $this->runAssertions($msg);
    }
}
