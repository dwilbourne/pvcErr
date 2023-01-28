<?php

declare (strict_types=1);
/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err;

use pvc\err\ExceptionLibraryUtils;
use PHPUnit\Framework\TestCase;
use pvcTests\err\fixture\SampleException;
use pvcTests\err\fixture\SampleNonException;

class ExceptionLibraryUtilsTest extends TestCase
{

    /**
     * testValidateExceptionClassStringReturnsNullIfNotReflectable
     * @covers \pvc\err\ExceptionLibraryUtils::validateExceptionClassString
     */
    public function testValidateExceptionClassStringReturnsNullIfNotReflectable(): void
    {
        self::assertNull(ExceptionLibraryUtils::validateExceptionClassString("foo"));
    }

    /**
     * testValidateExceptionClassStringReturnsNullIfNotAnException
     * @covers \pvc\err\ExceptionLibraryUtils::validateExceptionClassString
     */
    public function testValidateExceptionClassStringReturnsNullIfNotAnException(): void
    {
        self::assertNull(ExceptionLibraryUtils::validateExceptionClassString(SampleNonException::class));
    }

    /**
     * testValidateExceptionClassStringReturnsReflectionIfItIsAnException
     * @covers \pvc\err\ExceptionLibraryUtils::validateExceptionClassString
     */
    public function testValidateExceptionClassStringReturnsReflectionIfItIsAnException(): void
    {
        self::assertInstanceOf(\ReflectionClass::class, ExceptionLibraryUtils::validateExceptionClassString
        (SampleException::class));
    }

    protected function dataProvider() : array
    {
        /**
         * this file must be manually required because it will not autoload (unless we were to modify the autoload
         * section of composer.json).
         */
        require_once 'tests' . DIRECTORY_SEPARATOR . 'fixture' . DIRECTORY_SEPARATOR . 'ClassWithNoNamespace.php';

        return [
            ['tests/fixture/NotAClass.php', false],
            ['tests/fixture/ClassWithNoNamespace.php', 'ClassWithNoNamespace'],
            ['tests/fixture/SampleException.php', 'pvcTests\err\fixture\SampleException'],
        ];
    }

    /**
     * testGetClassStringFromFileReturnsFalseIfNotAnObject
     * @param string $filename
     * @param string|false $expectedResult
     * @dataProvider dataProvider
     * @covers \pvc\err\ExceptionLibraryUtils::getClassStringFromFile
     */
    public function testGetClassStringFromFileReturnsFalseIfNotAnObject(string $filename, $expectedResult): void
    {
        self::assertEquals($expectedResult, ExceptionLibraryUtils::getClassStringFromFile($filename));
    }
}
