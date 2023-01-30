<?php

declare (strict_types=1);
/**
 * @package {PROJECT_NAME}
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err;

use PHPUnit\Framework\MockObject\MockObject;
use pvc\err\XDataAbstract;
use PHPUnit\Framework\TestCase;
use pvcTests\err\fixture\SampleDuplicateException;
use pvcTests\err\fixture\SampleException;
use pvcTests\err\fixture\SampleNonException;

class XDataAbstractTest extends TestCase
{
    /**
     * @var XDataAbstract&MockObject
     */
    protected XDataAbstract&MockObject $mock;

    public function setUp(): void
    {
        $this->mock = $this->getMockForAbstractClass(XDataAbstract::class);
    }

    /**
     * testGetLocalCode
     * @covers \pvc\err\XDataAbstract::getLocalCode
     */
    public function testGetLocalCode(): void
    {
        $array = [SampleException::class => 1000, SampleDuplicateException::class => 1001];
        $this->mock->method('getLocalCodes')->willReturn($array);
        self::assertEquals(1000, $this->mock->getLocalCode(SampleException::class));
        self::assertEquals(0, $this->mock->getLocalCode(SampleNonException::class));
    }

    /**
     * testGetLocalMessage
     * @covers \pvc\err\XDataAbstract::getLocalMessage
     */
    public function testGetLocalMessage(): void
    {
        $string_1 = "this is a great string";
        $string_2 = "another hugely popular string";
        $array = [SampleException::class => $string_1, SampleDuplicateException::class => $string_2];
        $this->mock->method('getLocalMessages')->willReturn($array);
        self::assertEquals($string_1, $this->mock->getLocalMessage(SampleException::class));
        self::assertEquals("", $this->mock->getLocalMessage(SampleNonException::class));
    }

}
