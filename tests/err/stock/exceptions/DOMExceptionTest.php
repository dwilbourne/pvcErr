<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\DOMException;
use tests\err\ExceptionTester;

/**
 * Class DOMExceptionTest
 * @covers \pvc\err\stock\exceptions\DOMException
 */
class DOMExceptionTest extends ExceptionTester
{
    public function testDOMArgumentException(): void
    {
        $code = ExceptionConstants::DOM_EXCEPTION;
        $exception = new DOMException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
