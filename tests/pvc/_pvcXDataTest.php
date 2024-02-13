<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\pvc;

use pvc\err\pvc\_pvcXData;
use pvc\err\XDataTestMaster;

class _pvcXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\pvc\_pvcXData::getXMessageTemplates
     * @covers \pvc\err\pvc\_pvcXData::getLocalXCodes
     * @covers \pvc\err\pvc\InvalidArrayIndexException::__construct
     * @covers \pvc\err\pvc\InvalidArrayValueException::__construct
     * @covers \pvc\err\pvc\InvalidFilenameException::__construct
     * @covers \pvc\err\pvc\InvalidPHPVersionException::__construct
     * @covers \pvc\err\pvc\PregMatchFailureException::__construct
     * @covers \pvc\err\pvc\PregReplaceFailureException::__construct
     * @covers \pvc\err\pvc\UnsetAttributeException::__construct
     */
    public function testPvcExceptionLibrary(): void
    {
        $xData = new _pvcXData();
        self::assertTrue($this->verifyLibrary($xData));
    }
}
