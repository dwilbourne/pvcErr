<?php

declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\messages;


use pvc\err\stock\messages\DomainExceptionMsg;
use tests\err\MsgTester;

/**
 * Class DomainExceptionMsgTest
 * @covers \pvc\err\stock\messages\DomainExceptionMsg
 */
class DomainExceptionMsgTest extends MsgTester
{
    public function testDomainExceptionMsg(): void
    {
        $valueProvided = 'foo';
        $msg = new DomainExceptionMsg($valueProvided);
        $this->runAssertions($msg);
    }
}