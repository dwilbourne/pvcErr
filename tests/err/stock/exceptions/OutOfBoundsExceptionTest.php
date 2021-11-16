<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\OutOfBoundsException;
use tests\err\ExceptionTester;

/**
 * Class OutOfBoundsExceptionTest
 * @covers \pvc\err\stock\exceptions\OutOfBoundsException
 */
class OutOfBoundsExceptionTest extends ExceptionTester
{
    public function testOutOfBoundsException(): void
    {
        $code = ExceptionConstants::OUT_OF_BOUNDS_EXCEPTION;
        $exception = new OutOfBoundsException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
