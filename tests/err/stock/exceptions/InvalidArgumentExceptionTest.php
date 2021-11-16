<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\InvalidArgumentException;
use tests\err\ExceptionTester;

/**
 * Class InvalidArgumentExceptionTest
 * @covers \pvc\err\stock\exceptions\InvalidArgumentException
 */
class InvalidArgumentExceptionTest extends ExceptionTester
{
    public function testInvalidArgumentException(): void
    {
        $code = ExceptionConstants::INVALID_ARGUMENT_EXCEPTION;
        $exception = new InvalidArgumentException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
