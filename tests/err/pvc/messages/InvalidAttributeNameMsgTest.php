<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\InvalidAttributeNameMsg;
use tests\err\MsgTester;

/**
 * Class InvalidAttributeNameMsgTest
 * @covers \pvc\err\pvc\messages\InvalidAttributeNameMsg
 */
class InvalidAttributeNameMsgTest extends MsgTester
{
    public function testInvalidAttributeNameMsg(): void
    {
        $attributeName = 'foo';
        $msg = new InvalidAttributeNameMsg($attributeName);
        $this->runAssertions($msg);
    }
}
