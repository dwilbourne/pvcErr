<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err;

use Exception;
use PHPUnit\Framework\TestCase;
use pvcTests\err\fixtureForXDataTests\SampleException;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithMultipleParamTypes;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithNoConstructor;
use pvcTests\err\fixtureForXDataTestsButMissingXData\UnfinishedException;
use ReflectionException;
use stdClass;

#[\PHPUnit\Framework\Attributes\CoversMethod(\pvc\err\Exception::class, 'getClassStringFromFileContents')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\pvc\err\Exception::class, 'getXDataFromClassString')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\pvc\err\Exception::class, '__construct')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\pvc\err\Exception::class, 'parseParams')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\pvc\err\Exception::class, 'stringify')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\pvc\err\Exception::class, 'mapped_implode')]
class ExceptionTest extends TestCase
{
    /**
     * @function testExceptionWithoutExceptionDataThrowsException
     */
    public function testExceptionWithoutExceptionDataThrowsException(): void
    {
        $this->expectException(Exception::class);
        new UnfinishedException();
    }

    /**
     * @function testExceptionFindsXDataFileAndGetsCodeAndMessageProperly
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
     */
    public function testGetClassStringFromFileContentsReturnsFalseForFileWithNoClassDeclaration(): void
    {
        $fixture = __DIR__ . '/fixture/NotAClass.php';
        $msg = 'failed to assert that getClassStringFromFileContents returns false for file with no class declaration.';
        self::assertFalse(
            \pvc\err\Exception::getClassStringFromFileContents(
                file_get_contents($fixture)
            ),
            $msg
        );
    }

    /**
     * @function testExceptionGetsArgsCorrectWithPreviousExplicitlySet
     * @throws ReflectionException
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
     */
    public function testExceptionCorrectlySanitizesParametersBasedOnType(): void
    {
        $param1 = 'some string';
        $param2 = 42;
        $param3 = false;
        $param4 = ['foo'];
        $param5 = new stdClass();
        $param6 = new class {
            public function __toString(): string
            {
                return 'some other string';
            }
        };
        $e = new SampleExceptionWithMultipleParamTypes(
            $param1,
            $param2,
            $param3,
            $param4,
            $param5,
            $param6
        );
        $expectedMsg = '';
        $expectedMsg .= 'string parameter = some string, ';
        $expectedMsg .= 'int parameter = 42, ';
        $expectedMsg .= 'bool parameter = {bool (false)}, ';
        $expectedMsg .= "array parameter = [0=foo], ";
        $expectedMsg .= 'object parameter = O:8:"stdClass":0:{}, ';
        $expectedMsg .= 'object parameter = some other string.';
        self::assertEquals($expectedMsg, $e->getMessage());
    }
}
