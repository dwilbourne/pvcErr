<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\PregMatchFailureException;
use tests\err\ExceptionTester;

/**
 * Class PregMatchFailureExceptionTest
 * @covers \pvc\err\pvc\exceptions\PregMatchFailureException
 */
class PregMatchFailureExceptionTest extends ExceptionTester
{
    public function testPregMatchFailureException(): void
    {
        $code = ExceptionConstants::PREG_MATCH_FAILURE_EXCEPTION;
        $exception = new PregMatchFailureException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
