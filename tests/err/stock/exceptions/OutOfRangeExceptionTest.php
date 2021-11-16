<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\OutOfRangeException;
use tests\err\ExceptionTester;

/**
 * Class OutOfRangeExceptionTest
 * @covers \pvc\err\stock\exceptions\OutOfRangeException
 */
class OutOfRangeExceptionTest extends ExceptionTester
{
    public function testOutOfRangeException(): void
    {
        $code = ExceptionConstants::OUT_OF_RANGE_EXCEPTION;
        $exception = new OutOfRangeException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
