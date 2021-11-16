<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err\stock\exceptions;

use pvc\err\stock\ExceptionConstants;
use pvc\err\stock\exceptions\IntlException;
use tests\err\ExceptionTester;

/**
 * Class IntlExceptionTest
 * @covers \pvc\err\stock\exceptions\IntlException
 */
class IntlExceptionTest extends ExceptionTester
{
    public function testIntlException(): void
    {
        $code = ExceptionConstants::INTL_EXCEPTION;
        $exception = new IntlException($this->getMsg(), $this->getPreviousException());
        $this->runAssertions($code, $exception);
    }
}
