<?php

declare (strict_types=1);
/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\stock;

use pvc\err\stock\_StockExceptionLibraryData;
use pvcTests\err\ExceptionLibraryDataTestMaster;

class StockExceptionLibraryDataTest extends ExceptionLibraryDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\stock\_StockExceptionLibraryData::getDirectory
     * @covers \pvc\err\stock\_StockExceptionLibraryData::getNamespace
     * @covers \pvc\err\stock\_StockExceptionLibraryData::getLocalMessages
     * @covers \pvc\err\stock\_StockExceptionLibraryData::getLocalCodes
     * @covers \pvc\err\stock\_StockExceptionLibraryData::getNamespace
     * @covers \pvc\err\stock\_StockExceptionLibraryData::getDirectory
     */
    public function testPvcExceptionLibrary(): void
    {
        $this->verifylibrary(_StockExceptionLibraryData::class);
    }

}
