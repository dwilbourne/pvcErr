<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\UnsetAttributeException;
use tests\err\ExceptionTester;

/**
 * Class UnsetAttributeExceptionTest
 * @covers \pvc\err\pvc\exceptions\UnsetAttributeException
 */
class UnsetAttributeExceptionTest extends ExceptionTester
{
    public function testUnsetAttributeException(): void
    {
        $code = ExceptionConstants::UNSET_ATTRIBUTE_EXCEPTION;
        $exception = new UnsetAttributeException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
