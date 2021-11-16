<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\UnexpectedValueException;
use tests\err\ExceptionTester;

/**
 * Class UnexpectedValueExceptionTest
 * @covers \pvc\err\stock\exceptions\UnexpectedValueException
 */
class UnexpectedValueExceptionTest extends ExceptionTester
{
    public function testUnexpectedValueException(): void
    {
        $code = ExceptionConstants::UNEXPECTED_VALUE_EXCEPTION;
        $exception = new UnexpectedValueException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
