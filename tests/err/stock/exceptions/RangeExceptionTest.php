<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\RangeException;
use tests\err\ExceptionTester;

/**
 * Class RangeExceptionTest
 * @covers \pvc\err\stock\exceptions\RangeException
 */
class RangeExceptionTest extends ExceptionTester
{
    public function testRangeException(): void
    {
        $code = ExceptionConstants::RANGE_EXCEPTION;
        $exception = new RangeException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
