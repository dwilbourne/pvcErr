<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;


use pvc\err\stock\messages\DOMFunctionMsg;
use tests\err\MsgTester;

/**
 * Class DOMArgumentMsgTest
 * @covers \pvc\err\stock\messages\DOMFunctionMsg
 */
class DOMFunctionMsgTest extends MsgTester
{
    public function testDOMFunctionMsg(): void
    {
        $domFunctionName = 'foo';
        $msg = new DOMFunctionMsg($domFunctionName);
        $this->runAssertions($msg);
    }
}