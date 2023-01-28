<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\pvc;

use PHPUnit\Framework\TestCase;
use pvc\err\ExceptionFactory;
use pvc\err\pvc\InvalidArrayIndexException;
use pvc\err\pvc\InvalidArrayValueException;
use pvc\err\pvc\InvalidAttributeNameException;
use pvc\err\pvc\InvalidFilenameException;
use pvc\err\pvc\InvalidPHPVersionException;
use pvc\err\pvc\PregMatchFailureException;
use pvc\err\pvc\PregReplaceFailureException;

class PvcExceptionLibraryIntegration extends TestCase
{

    /**
     * setParams
     * @return array<class-string, array<mixed>>
     */
    protected array $params = [
            InvalidArrayIndexException::class => ['(IndexName)'],
            InvalidArrayValueException::class => ['(Value)'],
            InvalidAttributeNameException::class => ['(attribute name)'],
            InvalidFilenameException::class => ['(filename)'],
            InvalidPHPVersionException::class => ['(min php version)'],
            PregMatchFailureException::class => ['some bad regex', 'some subject'],
            PregReplaceFailureException::class => ['some bad regex', 'some subject', 'some replacement'],
    ];

    /**
     * testExceptions
     * @covers \pvc\err\pvc\_PvcExceptionData::getLocalMessages
     * @covers \pvc\err\pvc\_PvcExceptionData::getLocalCodes
     */
    public function testExceptions(): void
    {
        $libraryCodes = new ExceptionLibraryCodes();
        $exceptionData = new ExceptionLibraryData();
        $factory = new ExceptionFactory($libraryCodes, $exceptionData);

        foreach ($this->params as $classString => $paramArray) {
            $exception = $factory->createException($classString, $paramArray);
            self::assertTrue(0 < $exception->getCode());
            self::assertNotEmpty($exception->getMessage());
        }
    }

}
