<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\DOMFunctionException;
use tests\err\ExceptionTester;

/**
 * Class DOMFunctionExceptionTest
 * @covers \pvc\err\stock\exceptions\DOMFunctionException
 */
class DOMFunctionExceptionTest extends ExceptionTester
{
    public function testDOMArgumentException(): void
    {
        $code = ExceptionConstants::DOM_FUNCTION_EXCEPTION;
        $exception = new DOMFunctionException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
