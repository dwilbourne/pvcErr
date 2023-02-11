<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\err;

use pvc\err\err\_XData;
use pvc\err\err\XFactoryClassStringArgumentException;
use pvc\err\err\XFactoryMissingXDataException;
use pvcTests\err\XDataTestMaster;

class XDataTest extends XDataTestMaster
{
    /**
     * getExceptionClassStringsFromDir
     * @return string[]
     *
     * This override is clumsy but XCodePrefixes throws its own exceptions and cannot use a factory (in order
     * to prevent circular dependencies).  So the return array here is just hardcoded to return those classes in this
     * directory that belong to XFactory.  The three others that belong to XCodePrefix are skipped because the data
     * used to populate those exceptions is contained within XCodePrevfixes itself and not in _Xdata.
     */
    protected function getExceptionClassStringsFromDir(): array
    {
        return [
          XFactoryClassStringArgumentException::class,
          XFactoryMissingXDataException::class
        ];
    }

    /**
     * testPvcExceptionLibrary
     * @covers \pvc\err\err\_XData::getDirectory
     * @covers \pvc\err\err\_XData::getNamespace
     * @covers \pvc\err\err\_XData::getLocalXMessages
     * @covers \pvc\err\err\_XData::getLocalXCodes
     * @covers \pvc\err\err\_XData::getNamespace
     * @covers \pvc\err\err\_XData::getDirectory
     */
    public function testPvcExceptionLibrary(): void
    {
        $this->verifylibrary(_XData::class);
    }
}
