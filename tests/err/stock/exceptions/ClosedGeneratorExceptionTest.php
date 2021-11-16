<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\ClosedGeneratorException;
use tests\err\ExceptionTester;

/**
 * Class ClosedGeneratorExceptionTest
 * @covers \pvc\err\stock\exceptions\ClosedGeneratorException
 */
class ClosedGeneratorExceptionTest extends ExceptionTester
{
    public function testClosedGeneratorException(): void
    {
        $code = ExceptionConstants::CLOSED_GENERATOR_EXCEPTION;
        $exception = new ClosedGeneratorException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
