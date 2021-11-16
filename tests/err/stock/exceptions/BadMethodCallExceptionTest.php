<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\BadMethodCallException;
use tests\err\ExceptionTester;

/**
 * Class BadMethodCallExceptionTest
 * @covers \pvc\err\stock\exceptions\BadMethodCallException
 */
class BadMethodCallExceptionTest extends ExceptionTester
{
    public function testBadMethodCallException(): void
    {
        $code = ExceptionConstants::BAD_METHOD_CALL_EXCEPTION;
        $exception = new BadMethodCallException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
