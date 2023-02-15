<?php

declare(strict_types=1);

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\stock;

use Exception;
use PHPUnit\Framework\TestCase;
use pvcTests\err\fixtureForXDataTests\SampleException;
use pvcTests\err\fixtureForXDataTests\SampleExceptionDuplicate;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithMultipleParamTypes;
use pvcTests\err\fixtureForXDataTestsButMissingXData\UnfinishedException;
use ReflectionException;
use stdClass;

class ExceptionTest extends TestCase
{
    public function setUp(): void
    {
        /**
         * need to manually require this file since it is not namespaced, is not PSR-0 / PSR-4 compliant, and
         * therefore will not autoload.  Change the path as necessary to suit yourself for these tests.
         */
        require_once "I:\\www\\pvcException\\tests\\fixtureForXDataTests\\ClassWithNoNamespace.php";
    }

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
     * @function testExceptionGetsArgsCorrectWithPreviousExplicitlySet
     * @throws ReflectionException
     * @covers \pvc\err\stock\Exception::parseParams
     */
    public function testExceptionGetsArgsCorrectWithPreviousExplicitlySet(): void
    {
        $previousException = new SampleExceptionDuplicate();
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
        $expectedMessage = 'string parameter = some string, int parameter = 42, bool parameter = false, object parameter = object.';
        self::assertEquals($expectedMessage, $e->getMessage());
    }
}
