<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\RuntimeException;
use tests\err\ExceptionTester;

/**
 * Class RuntimeExceptionTest
 * @covers \pvc\err\stock\exceptions\RuntimeException
 */
class RuntimeExceptionTest extends ExceptionTester
{
    public function testRuntimeException(): void
    {
        $code = ExceptionConstants::RUNTIME_EXCEPTION;
        $exception = new RuntimeException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
