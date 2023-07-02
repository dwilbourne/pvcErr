<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\pvc;

use PHPUnit\Framework\TestCase;
use pvc\err\pvc\InvalidArrayIndexException;
use pvc\err\pvc\InvalidArrayValueException;
use pvc\err\pvc\InvalidFilenameException;
use pvc\err\pvc\InvalidPHPVersionException;
use pvc\err\pvc\PregMatchFailureException;
use pvc\err\pvc\PregReplaceFailureException;
use ReflectionException;

class PvcXLibIntegrationTest extends TestCase
{
    /**
     * @function testInvalidArrayIndexException
     * @throws ReflectionException
     * @covers \pvc\err\pvc\InvalidArrayIndexException::__construct
     */
    public function testInvalidArrayIndexException(): void
    {
        $badIndexName = 'badIndexName';
        self::assertInstanceOf(InvalidArrayIndexException::class, new InvalidArrayIndexException($badIndexName));
    }

    /**
     * @function testInvalidArrayValueException
     * @throws ReflectionException
     * @covers \pvc\err\pvc\InvalidArrayValueException::__construct
     */
    public function testInvalidArrayValueException(): void
    {
        $badArrayValue = 'foo';
        self::assertInstanceOf(InvalidArrayValueException::class, new InvalidArrayValueException($badArrayValue));
    }

    /**
     * @function testInvalidFilenameException
     * @throws ReflectionException
     * @covers \pvc\err\pvc\InvalidFilenameException::__construct()
     */
    public function testInvalidFilenameException(): void
    {
        /** not valid in Windows but OK in Unix / Linux etc */
        $badFilename = '-';
        self::assertInstanceOf(InvalidFilenameException::class, new InvalidFilenameException($badFilename));
    }

    /**
     * @function testInvalidPHPVersionException
     * @throws ReflectionException
     * @covers \pvc\err\pvc\InvalidPHPVersionException::__construct
     */
    public function testInvalidPHPVersionException(): void
    {
        $currentVersion = '5.4';
        $minVersion = '7.1';
        self::assertInstanceOf(
            InvalidPHPVersionException::class,
            new InvalidPHPVersionException(
                $currentVersion,
                $minVersion
            )
        );
    }

    /**
     * @function testPregMatchFailureException
     * @throws ReflectionException
     * @covers \pvc\err\pvc\PregMatchFailureException::__construct
     */
    public function testPregMatchFailureException(): void
    {
        $regex = '/bad^reg$';
        $subject = 'this is some silly string';
        self::assertInstanceOf(PregMatchFailureException::class, new PregMatchFailureException($regex, $subject));
    }

    /**
     * @function testPregReplaceFailureException
     * @throws ReflectionException
     * @covers \pvc\err\pvc\PregReplaceFailureException::__construct
     */
    public function testPregReplaceFailureException(): void
    {
        $regex = '/bad^reg$';
        $subject = 'this is some silly string';
        $replace = 'auto';
        self::assertInstanceOf(
            PregReplaceFailureException::class,
            new PregReplaceFailureException(
                $regex,
                $subject,
                $replace
            )
        );
    }
}
