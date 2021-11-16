<?php

declare(strict_types=1);
/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err;

use PHPUnit\Framework\TestCase;
use pvc\formatter\msg\FrmtrMsg;
use pvc\msg\MsgInterface;

/**
 * Class MsgTest
 */
class MsgTester extends TestCase
{
    protected FrmtrMsg $frmtr;

    public function setUp(): void
    {
        $this->frmtr = new FrmtrMsg();
    }

    public function runAssertions(MsgInterface $msg): void
    {
        $msgOutput = $this->frmtr->format($msg);
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }
}
