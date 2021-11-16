<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\UnderflowException;
use tests\err\ExceptionTester;

/**
 * Class UnderflowExceptionTest
 * @covers \pvc\err\stock\exceptions\UnderflowException
 */
class UnderflowExceptionTest extends ExceptionTester
{
    public function testUnderflowException(): void
    {
        $code = ExceptionConstants::UNDERFLOW_EXCEPTION;
        $exception = new UnderflowException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
