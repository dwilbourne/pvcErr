<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\InvalidValueException;
use tests\err\ExceptionTester;

/**
 * Class InvalidValueExceptionTest
 * @covers \pvc\err\pvc\exceptions\InvalidValueException
 */
class InvalidValueExceptionTest extends ExceptionTester
{
    public function testInvalidValueException(): void
    {
        $code = ExceptionConstants::INVALID_VALUE_EXCEPTION;
        $exception = new InvalidValueException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
