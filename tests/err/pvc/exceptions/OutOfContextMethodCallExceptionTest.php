<?php
declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\OutOfContextMethodCallException;
use tests\err\ExceptionTester;

/**
 * Class OutOfContextMethodCallExceptionTest
 * @covers \pvc\err\pvc\exceptions\OutOfContextMethodCallException
 */
class OutOfContextMethodCallExceptionTest extends ExceptionTester
{
    public function testOutOfContextMethodCallException(): void
    {
        $code = ExceptionConstants::OUT_OF_CONTEXT_METHOD_CALL_EXCEPTION;
        $exception = new OutOfContextMethodCallException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
