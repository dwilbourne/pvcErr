<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\ReflectionException;
use tests\err\ExceptionTester;

/**
 * Class ReflectionExceptionTest
 * @covers \pvc\err\stock\exceptions\ReflectionException
 */
class ReflectionExceptionTest extends ExceptionTester
{
    public function testReflectionException(): void
    {
        $code = ExceptionConstants::REFLECTION_EXCEPTION;
        $exception = new ReflectionException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
