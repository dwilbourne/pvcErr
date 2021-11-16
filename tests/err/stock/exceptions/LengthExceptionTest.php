<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\LengthException;
use tests\err\ExceptionTester;

/**
 * Class LengthExceptionTest
 * @covers \pvc\err\stock\exceptions\LengthException
 */
class LengthExceptionTest extends ExceptionTester
{
    public function testLengthException(): void
    {
        $code = ExceptionConstants::LENGTH_EXCEPTION;
        $exception = new LengthException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
