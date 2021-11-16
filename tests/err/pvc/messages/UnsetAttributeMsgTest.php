<?php
declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\messages;

use pvc\err\pvc\messages\UnsetAttributeMsg;
use tests\err\MsgTester;

/**
 * Class UnsetAttributeMsgTest
 * @covers \pvc\err\pvc\messages\UnsetAttributeMsg
 */
class UnsetAttributeMsgTest extends MsgTester
{
    public function testUnsetAttributeMsg(): void
    {
        $attributeName = 'foo';
        $msg = new UnsetAttributeMsg($attributeName);
        $this->runAssertions($msg);
    }
}
