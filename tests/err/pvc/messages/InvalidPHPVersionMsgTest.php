<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\InvalidPHPVersionMsg;
use tests\err\MsgTester;

/**
 * Class InvalidPHPVersionMsgTest
 * @covers \pvc\err\pvc\messages\InvalidPHPVersionMsg
 */
class InvalidPHPVersionMsgTest extends MsgTester
{
    public function testInvalidPhpVersionMsg(): void
    {
        $minPhpVersion = '7.0.0';
        $msg = new InvalidPhpVersionMsg($minPhpVersion);
        $this->runAssertions($msg);
    }
}
