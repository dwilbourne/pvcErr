<?php

declare (strict_types=1);
/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\pvc;

use pvc\err\pvc\_PvcXData;
use pvcTests\err\XDataTestMaster;

class PvcXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\pvc\_PvcXData::getDirectory
     * @covers \pvc\err\pvc\_PvcXData::getNamespace
     * @covers \pvc\err\pvc\_PvcXData::getLocalXMessages
     * @covers \pvc\err\pvc\_PvcXData::getLocalXCodes
     * @covers \pvc\err\pvc\_PvcXData::getNamespace
     * @covers \pvc\err\pvc\_PvcXData::getDirectory
     */
    public function testPvcExceptionLibrary(): void
    {
        $this->verifylibrary(_PvcXData::class);
    }
}
