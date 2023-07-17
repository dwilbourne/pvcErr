<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\stock;

use Exception;
use PHPUnit\Framework\TestCase;
use pvcTests\err\fixtureForXDataTests\SampleException;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithMultipleParamTypes;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithNoConstructor;
use pvcTests\err\fixtureForXDataTestsButMissingXData\UnfinishedException;
use ReflectionException;
use stdClass;

class ExceptionTest extends TestCase
{
    /**
     * @function testExceptionWithoutExceptionDataThrowsException
     * @covers \pvc\err\stock\Exception::getClassStringFromFileContents
     * @covers \pvc\err\stock\Exception::getXDataFromClassString
     * @covers \pvc\err\stock\Exception::__construct
     */
    public function testExceptionWithoutExceptionDataThrowsException(): void
    {
        $this->expectException(Exception::class);
        $e = new UnfinishedException();
    }

    /**
     * @function testExceptionFindsXDataFileAndGetsCodeAndMessageProperly
     * @covers \pvc\err\stock\Exception::getClassStringFromFileContents
     * @covers \pvc\err\stock\Exception::getXDataFromClassString
     * @covers \pvc\err\stock\Exception::__construct
     * @covers \pvc\err\stock\Exception::parseParams
     */
    public function testExceptionFindsXDataFileAndGetsCodeAndMessageProperly(): void
    {
        /**
         * the order of these parameters is backwards from how they appear in the message template
         */
        $e = new SampleException('bar', 'foo');
        $expectedMessage = 'some error message with parameter foo and parameter bar';
        $expectedCode = 9011001;
        self::assertEquals($expectedMessage, $e->getMessage());
        self::assertEquals($expectedCode, $e->getCode());
    }

    /**
     * testGetClassStringFromFileContentsReturnsFalseForFileWithNoClassDeclaration
     * @covers \pvc\err\stock\Exception::getClassStringFromFileContents
     */
    public function testGetClassStringFromFileContentsReturnsFalseForFileWithNoClassDeclaration(): void
    {
        $fixture = __DIR__ . '/fixture/NotAClass.php';
        $msg = 'failed to assert that getClassStringFromFileContents returns false for file with no class declaration.';
        self::assertFalse(\pvc\err\stock\Exception::getClassStringFromFileContents(file_get_contents($fixture)), $msg);
    }

    /**
     * @function testExceptionGetsArgsCorrectWithPreviousExplicitlySet
     * @throws ReflectionException
     * @covers \pvc\err\stock\Exception::parseParams
     */
    public function testExceptionGetsArgsCorrectWithPreviousExplicitlySet(): void
    {
        $previousException = new SampleExceptionWithNoConstructor();
        $e = new SampleException('bar', 'foo', $previousException);
        self::assertSame($previousException, $e->getPrevious());
    }

    /**
     * @function testExceptionGetsArgsCorrectWithPreviousExplicitlySetToNull
     * @throws ReflectionException
     * @covers \pvc\err\stock\Exception::parseParams
     */
    public function testExceptionGetsArgsCorrectWithPreviousExplicitlySetToNull(): void
    {
        $previousException = null;
        $e = new SampleException('bar', 'foo', $previousException);
        self::assertSame($previousException, $e->getPrevious());
    }

    /**
     * @function testExceptionCorrectlySanitizesParametersBasedOnType
     * @throws ReflectionException
     * @covers \pvc\err\stock\Exception::sanitizeParameterValue
     */
    public function testExceptionCorrectlySanitizesParametersBasedOnType(): void
    {
        $param1 = 'some string';
        $param2 = 42;
        $param3 = false;
        $param4 = new stdClass();
        $e = new SampleExceptionWithMultipleParamTypes($param1, $param2, $param3, $param4);
        $expectedMsg = '';
        $expectedMsg .= 'string parameter = some string, ';
        $expectedMsg .= 'int parameter = 42, ';
        $expectedMsg .= 'bool parameter = false, ';
        $expectedMsg .= 'object parameter = object.';
        self::assertEquals($expectedMsg, $e->getMessage());
    }
}
