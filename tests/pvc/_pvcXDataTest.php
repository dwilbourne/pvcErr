<?php

declare(strict_types=1);

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err\pvc;

use pvc\err\pvc\_pvcXData;
use pvcTests\err\XDataTestMaster;

class _pvcXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\pvc\_pvcXData::getXMessageTemplates
     * @covers \pvc\err\pvc\_pvcXData::getXMessageTemplate
     * @covers \pvc\err\pvc\_pvcXData::getLocalXCodes
     * @covers \pvc\err\pvc\_pvcXData::getLocalXCode
     */
    public function testPvcExceptionLibrary(): void
    {
        $this->verifylibrary(_pvcXData::class);
    }
}
