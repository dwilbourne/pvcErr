<?php

/**
 * @package {PROJECT_NAME}
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use pvc\err\XDataAbstract;
use pvcTests\err\fixtureForXDataTests\SampleException;
use pvcTests\err\fixtureForXDataTests\SampleExceptionDuplicate;
use pvcTests\err\fixtureForXDataTests\SampleNonException;

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
     * @covers \pvc\err\XDataAbstract::getLocalXCode
     */
    public function testGetLocalXCode(): void
    {
        $array = [SampleException::class => 1000, SampleExceptionDuplicate::class => 1001];
        $this->mock->method('getLocalXCodes')->willReturn($array);
        self::assertEquals(1000, $this->mock->getLocalXCode(SampleException::class));
        self::assertEquals(0, $this->mock->getLocalXCode(SampleNonException::class));
    }

    /**
     * testGetLocalMessage
     * @covers \pvc\err\XDataAbstract::getXMessageTemplate
     */
    public function testGetXMessageTemplate(): void
    {
        $string_1 = 'this is a great string';
        $string_2 = 'another hugely popular string';
        $array = [SampleException::class => $string_1, SampleExceptionDuplicate::class => $string_2];
        $this->mock->method('getXMessageTemplates')->willReturn($array);
        self::assertEquals($string_1, $this->mock->getXMessageTemplate(SampleException::class));
        self::assertEquals('', $this->mock->getXMessageTemplate(SampleNonException::class));
    }

    /**
     * @function testCountXMessageVariables
     * @param string $message
     * @param int $expectNumParameters
     * @covers       \pvc\err\XDataAbstract::countXMessageVariables
     * @dataProvider dataProvider
     */
    public function testCountXMessageVariables(string $message, int $expectNumParameters): void
    {
        self::assertEquals($expectNumParameters, $this->mock->countXMessageVariables($message));
    }

    /**
     * @function dataProvider
     * @return array<string, array<string|int>>
     */
    protected function dataProvider(): array
    {
        return [
            'messageWithNoParameters' => ['This is a test message', 0],
            'messageWithOneParameter' => ['Your function parameter ${param} is invalid.', 1],
            'messageWithTwoParameters' => ['preg match failed.  regex = ${regex}, subject = ${subject}', 2],
            'messageWithThreeParameters' => [
                'preg replace failed.  regex = ${regex}, subject = ${subject}, replace = ${replace}',
                3
            ],
            'messageWithMalformedParameter' => ['Your function parameter ${param is invalid.', 0],
        ];
    }
}
