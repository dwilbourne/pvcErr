<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\InvalidTypeException;
use tests\err\ExceptionTester;

/**
 * Class InvalidTypeExceptionTest
 * @covers \pvc\err\pvc\exceptions\InvalidTypeException
 */
class InvalidTypeExceptionTest extends ExceptionTester
{
    public function testInvalidTypeException(): void
    {
        $code = ExceptionConstants::INVALID_TYPE_EXCEPTION;
        $exception = new InvalidTypeException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
