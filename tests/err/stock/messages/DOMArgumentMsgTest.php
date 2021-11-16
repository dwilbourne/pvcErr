<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;


use pvc\err\stock\messages\DOMArgumentMsg;
use tests\err\MsgTester;

/**
 * Class DOMArgumentMsgTest
 * @covers \pvc\err\stock\messages\DOMArgumentMsg
 */
class DOMArgumentMsgTest extends MsgTester
{
    public function testDOMArgumentMsg(): void
    {
        $domFunctionArgument = 'foo';
        $domFunctionName = 'bar';
        $msg = new DOMArgumentMsg($domFunctionArgument, $domFunctionName);
        $this->runAssertions($msg);
    }
}