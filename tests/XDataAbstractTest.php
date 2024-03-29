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
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithNoConstructor;
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
        $array = [SampleException::class => 1000, SampleExceptionWithNoConstructor::class => 1001];
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
        $array = [SampleException::class => $string_1, SampleExceptionWithNoConstructor::class => $string_2];
        $this->mock->method('getXMessageTemplates')->willReturn($array);
        self::assertEquals($string_1, $this->mock->getXMessageTemplate(SampleException::class));
        self::assertEquals('', $this->mock->getXMessageTemplate(SampleNonException::class));
    }

    /**
     * @function testCountXMessageVariables
     * @param string $message
     * @param array $parameters
     * @covers       \pvc\err\XDataAbstract::getXMessageVariables
     * @dataProvider dataProvider
     */
    public function testGetXMessageVariables(string $message, array $parameters): void
    {
        self::assertEqualsCanonicalizing($parameters, $this->mock->getXMessageVariables($message));
    }

    /**
     * @function dataProvider
     * @return array<string, array<string|int>>
     */
    protected function dataProvider(): array
    {
        return [
            'messageWithNoParameters' => ['This is a test message', []],
            'messageWithOneParameter' => ['Your function parameter ${param} is invalid.', ['${param}']],
            'messageWithTwoParameters' => ['preg match failed.  regex = ${regex}, subject = ${subject}', ['${regex}', '${subject}']],
            'messageWithThreeParameters' => ['preg replace failed.  regex = ${regex}, subject = ${subject}, replace = ${replace}', ['${regex}', '${subject}', '${replace}']],
            'messageWithMalformedParameter' => ['Your function parameter ${param is invalid.', []],
        ];
    }
}
