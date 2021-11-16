<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\PregReplaceFailureException;
use tests\err\ExceptionTester;

/**
 * Class PregReplaceFailureExceptionTest
 * @covers \pvc\err\pvc\exceptions\PregReplaceFailureException
 */
class PregReplaceFailureExceptionTest extends ExceptionTester
{
    public function testPregReplaceFailureException(): void
    {
        $code = ExceptionConstants::PREG_REPLACE_FAILURE_EXCEPTION;
        $exception = new PregReplaceFailureException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
