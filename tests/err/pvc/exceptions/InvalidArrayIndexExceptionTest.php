<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\InvalidArrayIndexException;
use tests\err\ExceptionTester;

/**
 * Class InvalidArrayIndexExceptionTest
 * @covers \pvc\err\pvc\exceptions\InvalidArrayIndexException
 */
class InvalidArrayIndexExceptionTest extends ExceptionTester
{
    public function testInvalidArrayIndexException(): void
    {
        $code = ExceptionConstants::INVALID_ARRAY_INDEX_EXCEPTION;
        $exception = new InvalidArrayIndexException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
