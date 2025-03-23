<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\pvc\array;

use pvc\err\pvc\array\_pvcArrayXData;
use pvc\err\XDataTestMaster;

class _pvcArrayXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\pvc\php\_pvcPhpXData::getXMessageTemplates
     * @covers \pvc\err\pvc\php\_pvcPhpXData::getLocalXCodes
     *
     * @covers \pvc\err\pvc\array\InvalidArrayIndexException
     * @covers \pvc\err\pvc\array\InvalidArrayValueException
     */
    public function testPvcExceptionLibrary(): void
    {
        $xData = new _pvcArrayXData();
        self::assertTrue($this->verifyLibrary($xData));
    }
}
