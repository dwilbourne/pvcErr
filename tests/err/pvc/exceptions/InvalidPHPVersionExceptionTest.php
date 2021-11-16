<?php
declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\pvc\exceptions;

use pvc\err\pvc\ExceptionConstants;
use pvc\err\pvc\exceptions\InvalidPHPVersionException;
use tests\err\ExceptionTester;

/**
 * Class InvalidPHPVersionExceptionTest
 * @covers \pvc\err\pvc\exceptions\InvalidPHPVersionException
 */
class InvalidPHPVersionExceptionTest extends ExceptionTester
{
    public function testInvalidPhpVersionException(): void
    {
        $code = ExceptionConstants::INVALID_PHP_VERSION_EXCEPTION;
        $exception = new InvalidPHPVersionException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
