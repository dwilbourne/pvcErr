<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\pvc;

use PHPUnit\Framework\TestCase;
use pvc\err\XFactory;
use pvc\err\XCodePrefixes;
use pvc\err\pvc\InvalidArrayIndexException;
use pvc\err\pvc\InvalidArrayValueException;
use pvc\err\pvc\InvalidAttributeNameException;
use pvc\err\pvc\InvalidFilenameException;
use pvc\err\pvc\InvalidPHPVersionException;
use pvc\err\pvc\PregMatchFailureException;
use pvc\err\pvc\PregReplaceFailureException;

class PvcXLibIntegrationTest extends TestCase
{

    /**
     * @var array<class-string, array<int, string>> $params
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
     * @covers \pvc\err\pvc\_PvcXLibData::getLocalXMessages
     * @covers \pvc\err\pvc\_PvcXLibData::getLocalXCodes
     */
    public function testExceptions(): void
    {
        $libraryCodes = new XCodePrefixes();
        $factory = new XFactory($libraryCodes);

        foreach ($this->params as $classString => $paramArray) {
            $exception = $factory->createException($classString, $paramArray);
            self::assertTrue(0 < $exception->getCode());
            self::assertNotEmpty($exception->getMessage());
        }
    }

}
