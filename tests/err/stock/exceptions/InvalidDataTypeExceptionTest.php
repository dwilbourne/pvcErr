<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\InvalidDataTypeException;
use tests\err\ExceptionTester;

/**
 * Class InvalidDataTypeExceptionTest
 * @covers \pvc\err\stock\exceptions\InvalidDataTypeException
 */
class InvalidDataTypeExceptionTest extends ExceptionTester
{
    public function testInvalidDataTypeException(): void
    {
        $code = ExceptionConstants::INVALID_DATA_TYPE_EXCEPTION;
        $exception = new InvalidDataTypeException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
