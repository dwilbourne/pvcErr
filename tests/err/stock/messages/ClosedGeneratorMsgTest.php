<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;


use pvc\err\stock\messages\ClosedGeneratorMsg;
use tests\err\MsgTester;

/**
 * Class ClosedGeneratorMsgTest
 * @covers \pvc\err\stock\messages\ClosedGeneratorMsg
 */
class ClosedGeneratorMsgTest extends MsgTester
{
    public function testClosedGeneratorMsg(): void
    {
        $generatorName = 'foo';
        $msg = new ClosedGeneratorMsg($generatorName);
        $this->runAssertions($msg);
    }
}