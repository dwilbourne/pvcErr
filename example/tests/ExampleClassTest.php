<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcExamples\err\tests;

use PHPUnit\Framework\TestCase;
use pvcExamples\err\src\err\MyException;
use pvcExamples\err\src\ExampleClass;
use Throwable;

class ExampleClassTest extends TestCase
{
    /**
     * testClientClassThrowsException
     * @throws Throwable
     * @covers \pvcExamples\err\src\ExampleClass::ThrowAnException
     */
    public function testExampleClassThrowsException(): void
    {
        $this->expectException(MyException::class);
        $exampleClass = new ExampleClass();
        $exampleClass->ThrowAnException();
    }
}
