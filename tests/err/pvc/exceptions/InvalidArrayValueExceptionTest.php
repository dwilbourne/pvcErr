<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\InvalidArrayValueException;
use tests\err\ExceptionTester;

/**
 * Class InvalidArrayValueExceptionTest
 * @covers \pvc\err\pvc\exceptions\InvalidArrayValueException
 */
class InvalidArrayValueExceptionTest extends ExceptionTester
{
    public function testInvalidArrayValueException(): void
    {
        $code = ExceptionConstants::INVALID_ARRAY_VALUE_EXCEPTION;
        $exception = new InvalidArrayValueException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }

}
