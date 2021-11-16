<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\OverflowException;
use tests\err\ExceptionTester;

/**
 * Class OverflowExceptionTest
 * @covers \pvc\err\stock\exceptions\OverflowException
 */
class OverflowExceptionTest extends ExceptionTester
{
    public function testOverflowException(): void
    {
        $code = ExceptionConstants::OVERFLOW_EXCEPTION;
        $exception = new OverflowException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
