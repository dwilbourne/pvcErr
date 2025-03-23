<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\pvc\file;

use pvc\err\pvc\file\_pvcFileXData;
use pvc\err\XDataTestMaster;

class _pvcFileXDataTest extends XDataTestMaster
{
    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\pvc\php\_pvcPhpXData::getXMessageTemplates
     * @covers \pvc\err\pvc\php\_pvcPhpXData::getLocalXCodes
     *
     * file-oriented exceptions
     * @covers \pvc\err\pvc\file\InvalidFilenameException::__construct
     * @covers \pvc\err\pvc\file\FileDoesNotExistException
     * @covers \pvc\err\pvc\file\FileNotReadableException
     */
    public function testPvcExceptionLibrary(): void
    {
        $xData = new _pvcFileXData();
        self::assertTrue($this->verifyLibrary($xData));
    }
}
