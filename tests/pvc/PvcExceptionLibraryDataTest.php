<?php

declare (strict_types=1);
/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\pvc;

use pvc\err\pvc\_PvcExceptionLibraryData;
use pvcTests\err\ExceptionLibraryDataTestMaster;

class PvcExceptionLibraryDataTest extends ExceptionLibraryDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\pvc\_PvcExceptionLibraryData::getDirectory
     * @covers \pvc\err\pvc\_PvcExceptionLibraryData::getNamespace
     * @covers \pvc\err\pvc\_PvcExceptionLibraryData::getLocalMessages
     * @covers \pvc\err\pvc\_PvcExceptionLibraryData::getLocalCodes
     * @covers \pvc\err\pvc\_PvcExceptionLibraryData::getNamespace
     * @covers \pvc\err\pvc\_PvcExceptionLibraryData::getDirectory
     */
    public function testPvcExceptionLibrary(): void
    {
        $this->verifylibrary(_PvcExceptionLibraryData::class);
    }
}
