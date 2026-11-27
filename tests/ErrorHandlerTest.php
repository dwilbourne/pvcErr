<?php

namespace pvcTests\err;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use pvc\err\ErrorHandler;
use pvc\err\stock\ErrorException;


class ErrorHandlerTest extends TestCase
{
    protected ErrorHandler $handler;

    public static function severityDataProvider(): array
    {
        return [
            [ErrorHandler::NOTICE, 'NOTICE'],
            [ErrorHandler::WARNING, 'WARNING'],
            [ErrorHandler::PARSE, 'PARSE'],
            [ErrorHandler::FATAL, 'FATAL'],
            [17, '(undefined error severity)'],
        ];
    }

    /**
     * @return array<int, int>
     */
    public static function errorTypeProvider(): array
    {
        return [
            [E_USER_DEPRECATED, ErrorHandler::NOTICE],
            [E_USER_WARNING, ErrorHandler::WARNING],
            [E_PARSE, ErrorHandler::PARSE],
            [E_RECOVERABLE_ERROR, ErrorHandler::FATAL],
            [485490857, ErrorHandler::UNDEFINED],
        ];
    }

    public function setUp(): void
    {
        $this->handler = new ErrorHandler();
    }

    /**
     * @return void
     * E_STRICT is deprecated as of PHP 8.4.
     * TODO: remove E_STRICT when it is removed from the language
     */
    #[CoversMethod(ErrorHandler::class, 'friendlyErrorType')]
    public function testFriendlyErrorTypeReturnsString(): void
    {
        $testErrorType = E_DEPRECATED;
        self::assertIsString($this->handler::friendlyErrorType($testErrorType));
    }

    /**
     * @return void
     */
    #[CoversMethod(ErrorHandler::class, 'friendlyErrorType')]
    public function testFriendlyErrorTypeReturnsUnknownWithBadType(): void
    {
        $testErrorType = 7346583764;
        $expectedResult = '(undefined error type)';
        self::assertEquals(
            $expectedResult,
            $this->handler::friendlyErrorType($testErrorType)
        );
    }

    /**
     * @return void
     */
    #[CoversMethod(ErrorHandler::class, 'friendlyErrorSeverity')]
    #[DataProvider('severityDataProvider')]
    public function testFriendlyErrorSeverityReturnsString(
        int $severity,
        string $label
    ): void {
        self::assertEquals(
            $label,
            $this->handler::friendlyErrorSeverity($severity)
        );
    }

    /**
     * @return void
     */
    #[CoversMethod(ErrorHandler::class, 'friendlyErrorSeverity')]
    #[CoversMethod(ErrorHandler::class, 'getErrorSeverity')]
    public function testFriendlyErrorSeverityReturnsUnknownWithBadSeverity(
    ): void
    {
        $testErrorSeverity = 2786785;
        $expectedResult = '(undefined error severity)';
        self::assertEquals(
            $expectedResult,
            $this->handler::friendlyErrorSeverity($testErrorSeverity)
        );
    }

    /**
     * @param  int  $errorType
     * @param  int  $expectedSeverity
     *
     * @return void
     */
    #[DataProvider('errorTypeProvider')]
    public function testGetSeverityReturnsInteger(
        int $errorType,
        int $expectedSeverity
    ): void {
        self::assertEquals(
            $expectedSeverity,
            ErrorHandler::getErrorSeverity($errorType)
        );
    }


    /**
     * @return void
     */
    #[CoversMethod(ErrorHandler::class, 'handle')]
    public function testHandle(): void
    {
        $errstr = 'some error string';
        $errno = E_USER_ERROR;

        set_error_handler($this->handler->handle(...));
        self::expectException(ErrorException::class);
        trigger_error($errstr, $errno);
    }
}
