<?php

declare(strict_types=1);

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\pvc;

use PHPUnit\Framework\TestCase;
use pvc\err\pvc\_Pvc_ExceptionFactory;
use pvc\err\pvc\InvalidArrayIndexException;
use pvc\err\pvc\InvalidArrayValueException;
use pvc\err\pvc\InvalidAttributeNameException;
use pvc\err\pvc\InvalidFilenameException;
use pvc\err\pvc\InvalidPHPVersionException;
use pvc\err\pvc\PregMatchFailureException;
use pvc\err\pvc\PregReplaceFailureException;

class PvcExceptionFactoryTest extends TestCase
{
	/**
	 * @var array<string, array<mixed>>
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
     * @covers _Pvc_ExceptionFactory::createException
     */
    public function testExceptions(): void
    {
        foreach ($this->params as $classString => $paramArray) {
            $exception = _Pvc_ExceptionFactory::createException($classString, $paramArray);
            self::assertTrue(0 < $exception->getCode());
            self::assertNotEmpty($exception->getMessage());
        }
    }
}
