<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\ErrorException;
use tests\err\ExceptionTester;

/**
 * Class ErrorExceptionTest
 * @covers \pvc\err\stock\exceptions\ErrorException
 */
class ErrorExceptionTest extends ExceptionTester
{
    public function testDOMArgumentException(): void
    {
        $code = ExceptionConstants::ERROR_EXCEPTION;
        $exception = new ErrorException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
