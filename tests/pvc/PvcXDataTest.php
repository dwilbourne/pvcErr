<?php

declare (strict_types=1);
/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\pvc;

use pvc\err\pvc\_PvcXLibData;
use pvcTests\err\XDataTestMaster;

class PvcXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\pvc\_PvcXLibData::getDirectory
     * @covers \pvc\err\pvc\_PvcXLibData::getNamespace
     * @covers \pvc\err\pvc\_PvcXLibData::getLocalMessages
     * @covers \pvc\err\pvc\_PvcXLibData::getLocalCodes
     * @covers \pvc\err\pvc\_PvcXLibData::getNamespace
     * @covers \pvc\err\pvc\_PvcXLibData::getDirectory
     */
    public function testPvcExceptionLibrary(): void
    {
        $this->verifylibrary(_PvcXLibData::class);
    }
}
