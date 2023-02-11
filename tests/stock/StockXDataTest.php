<?php

declare (strict_types=1);
/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\stock;

use pvc\err\stock\_StockXData;
use pvcTests\err\XDataTestMaster;

class StockXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\stock\_StockXData::getDirectory
     * @covers \pvc\err\stock\_StockXData::getNamespace
     * @covers \pvc\err\stock\_StockXData::getLocalXMessages
     * @covers \pvc\err\stock\_StockXData::getLocalXCodes
     * @covers \pvc\err\stock\_StockXData::getNamespace
     * @covers \pvc\err\stock\_StockXData::getDirectory
     */
    public function testPvcExceptionLibrary(): void
    {
        $this->verifylibrary(_StockXData::class);
    }

}
