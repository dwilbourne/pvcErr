<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\err;

use pvc\err\err\_ErrXData;
use pvc\err\XDataTestMaster;

/**
 * Class _ConfigXDataTest
 */
class _ConfigXDataTest extends XDataTestMaster
{
    /**
     * testLibrary
     * @covers \pvc\err\err\_ErrXData::getLocalXCodes
     * @covers \pvc\err\err\_ErrXData::getXMessageTemplates
     * @covers \pvc\err\err\InvalidXCodePrefixNumberException::__construct
     */
    public function testLibrary(): void
    {
        $xData = new _ErrXData();
        self::assertTrue($this->verifyLibrary($xData));
    }
}
