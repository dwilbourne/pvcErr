<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\DomainException;
use tests\err\ExceptionTester;

/**
 * Class DomainExceptionTest
 * @covers \pvc\err\stock\exceptions\DomainException
 */
class DomainExceptionTest extends ExceptionTester
{
    public function testDomainException(): void
    {
        $code = ExceptionConstants::DOMAIN_EXCEPTION;
        $exception = new DomainException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
