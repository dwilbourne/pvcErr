<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\BadFunctionCallException;
use tests\err\ExceptionTester;

/**
 * Class BadFunctionCallExceptionTest
 * @covers \pvc\err\stock\exceptions\BadFunctionCallException
 */
class BadFunctionCallExceptionTest extends ExceptionTester
{
    public function testBadFunctionCallException(): void
    {
        $code = ExceptionConstants::BAD_FUNCTION_CALL_EXCEPTION;
        $exception = new BadFunctionCallException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
