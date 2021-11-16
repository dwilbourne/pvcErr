<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\DOMArgumentException;
use tests\err\ExceptionTester;

/**
 * Class DOMArgumentExceptionTest
 * @covers \pvc\err\stock\exceptions\DOMArgumentException
 */
class DOMArgumentExceptionTest extends ExceptionTester
{
    public function testDOMArgumentException(): void
    {
        $code = ExceptionConstants::DOM_ARGUMENT_EXCEPTION;
        $exception = new DOMArgumentException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
