<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\pvc\php;

use pvc\err\pvc\php\_pvcPhpXData;
use pvc\err\XDataTestMaster;

class _pvcPhpXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\pvc\php\_pvcPhpXData::getXMessageTemplates
     * @covers \pvc\err\pvc\php\_pvcPhpXData::getLocalXCodes
     *
     * php-oriented exceptions
     * @covers \pvc\err\pvc\php\InvalidPHPVersionException::__construct
     */
    public function testPvcExceptionLibrary(): void
    {
        $xData = new _pvcPhpXData();
        self::assertTrue($this->verifyLibrary($xData));
    }
}
