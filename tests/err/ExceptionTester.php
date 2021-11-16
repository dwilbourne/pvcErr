<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err;

use Mockery;
use PHPUnit\Framework\TestCase;
use pvc\err\Exception;
use pvc\msg\Msg;

/**
 * Class ExceptionTest
 */
class ExceptionTester extends TestCase
{
    protected $msg;
    protected $previousException;

    public function setUp(): void
    {
        $this->previousException = Mockery::mock(\Exception::class);
        $this->msg = Mockery::mock(Msg::class);
    }

    public function runAssertions(int $code, Exception $exception): void
    {
        self::assertEquals($this->getMsg(), $exception->getMsg());
        self::assertEquals($code, $exception->getCode());
        self::assertEquals($this->getPreviousException(), $exception->getPrevious());
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function getPreviousException()
    {
        return $this->previousException;
    }
}
