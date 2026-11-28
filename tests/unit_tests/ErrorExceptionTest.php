<?php

namespace pvcTests\err\unit_tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use pvc\err\ErrorException;

#[CoversClass(ErrorException::class)]
class ErrorExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruction(): void
    {
        $exception = new ErrorException();
        self::assertInstanceOf(ErrorException::class, $exception);
    }

    /**
     * @return void
     */
    public function testGetFriendlyErrorType(): void
    {
        $msg = 'foo';
        /**
         * easy to forget the exception code!
         */
        $exceptionCode = 0;
        $errorType = E_USER_WARNING;
        $exception = new ErrorException($msg, $exceptionCode, $errorType);
        $expectedType = 'E_USER_WARNING';
        self::assertEquals($expectedType, $exception->getFriendlyType());
    }

    /**
     * @return void
     */
    public function testGetFriendlyErrorSeverity(): void
    {
        $msg = 'foo';
        /**
         * easy to forget the exception code!
         */
        $exceptionCode = 0;
        $errorType = E_USER_WARNING;
        $exception = new ErrorException($msg, $exceptionCode, $errorType);
        $expectedSeverity = 'WARNING';
        self::assertEquals(
            $expectedSeverity,
            $exception->getFriendlySeverity()
        );
    }
}
