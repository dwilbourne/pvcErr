<?php

declare (strict_types=1);
/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\stock;

use pvc\err\stock\_StockXLibData;
use pvcTests\err\XDataTestMaster;

class StockXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\stock\_StockXLibData::getDirectory
     * @covers \pvc\err\stock\_StockXLibData::getNamespace
     * @covers \pvc\err\stock\_StockXLibData::getLocalMessages
     * @covers \pvc\err\stock\_StockXLibData::getLocalCodes
     * @covers \pvc\err\stock\_StockXLibData::getNamespace
     * @covers \pvc\err\stock\_StockXLibData::getDirectory
     */
    public function testPvcExceptionLibrary(): void
    {
        $this->verifylibrary(_StockXLibData::class);
    }

}
