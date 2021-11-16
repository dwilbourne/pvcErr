<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\LogicException;
use tests\err\ExceptionTester;

/**
 * Class LogicExceptionTest
 * @covers \pvc\err\stock\exceptions\LogicException
 */
class LogicExceptionTest extends ExceptionTester
{
    public function testLogicException(): void
    {
        $code = ExceptionConstants::LOGIC_EXCEPTION;
        $exception = new LogicException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
