<?php
declare (strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\InvalidAttributeNameException;
use tests\err\ExceptionTester;

/**
 * Class InvalidAttributeNameExceptionTest
 * @covers \pvc\err\pvc\exceptions\InvalidAttributeNameException
 */
class InvalidAttributeNameExceptionTest extends ExceptionTester
{
    public function testInvalidAttributeNameException(): void
    {
        $code = ExceptionConstants::INVALID_ATTRIBUTE_NAME_EXCEPTION;
        $exception = new InvalidAttributeNameException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
