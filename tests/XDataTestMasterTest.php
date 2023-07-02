<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err;

use PHPUnit\Framework\TestCase;
use pvc\err\XDataTestMaster;
use pvcTests\err\fixturesForXDataTestMaster\allGood\_pvcXData;

class XDataTestMasterTest extends TestCase
{
    protected XDataTestMaster $xDataTestMaster;

    protected string $fixtureDir;

    public function setUp(): void
    {
        $this->xDataTestMaster = new XDataTestMaster();
        $this->fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixturesForXDataTestMaster';
    }

    /**
     * testGetExceptionClassStrings
     * @covers \pvc\err\XDataTestMaster::getExceptionClassStrings
     * @covers \pvc\err\XDataTestMaster::getClassStringFromFile
     */
    public function testGetExceptionClassStrings(): void
    {
        $xData = new _pvcXData();
        /**
         * There are 10 files in the fixture dir, 6 of which are exceptions
         */
        self::assertEquals(6, count($this->xDataTestMaster->getExceptionClassStrings($xData)));
    }

    /**
     * testGetExceptionClassStringsReturnsEmptyArrayOnEmptyDirectory
     * @covers \pvc\err\XDataTestMaster::getExceptionClassStrings
     */
    public function testGetExceptionClassStringsReturnsEmptyArrayOnEmptyDirectory(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\noExceptionsDefined\_pvcXData();
        $exceptionClassStrings = $this->xDataTestMaster->getExceptionClassStrings($xData);
        self::assertIsArray($exceptionClassStrings);
        self::assertEquals(0, count($exceptionClassStrings));
    }

    /**
     * testVerifyKeysMatchClassStringsFromDirFailsWithMoreCodesThanExceptions
     * @covers \pvc\err\XDataTestMaster::verifyKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDirFailsWithMoreCodesThanExceptions(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\moreCodesThanExceptions\_pvcXData();
        self::expectOutputRegex('/codes*/');
        self::assertFalse($this->xDataTestMaster->verifyKeysMatchClassStringsFromDir($xData));
    }

    /**
     * testVerifyKeysMatchClassStringsFromDirFailsWithMoreExceptionsThanCodes
     * @covers \pvc\err\XDataTestMaster::verifyKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDirFailsWithMoreExceptionsThanCodes(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\moreExceptionsThanCodes\_pvcXData();
        self::expectOutputRegex('/exception*/');
        self::assertFalse($this->xDataTestMaster->verifyKeysMatchClassStringsFromDir($xData));
    }

    /**
     * testVerifyKeysMatchClassStringsFromDirFailsWithMoreExceptionsThanMessages
     * @covers \pvc\err\XDataTestMaster::verifyKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDirFailsWithMoreExceptionsThanMessages(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\moreExceptionsThanMessages\_pvcXData();
        self::expectOutputRegex('/exception*/');
        self::assertFalse($this->xDataTestMaster->verifyKeysMatchClassStringsFromDir($xData));
    }

    /**
     * testVerifyKeysMatchClassStringsFromDirFailsWithMoreMessagesThanExceptions
     * @covers \pvc\err\XDataTestMaster::verifyKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDirFailsWithMoreMessagesThanExceptions(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\moreMessagesThanExceptions\_pvcXData();
        self::expectOutputRegex('/messages*/');
        self::assertFalse($this->xDataTestMaster->verifyKeysMatchClassStringsFromDir($xData));
    }

    /**
     * testVerifyGetLocalCodesArrayHasUniqueIntegerValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalCodesArrayHasUniqueIntegerValues
     */
    public function testVerifyGetLocalCodesArrayHasIntegerValues(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\codesNotIntegers\_pvcXData();
        self::expectOutputRegex('/not all exception codes are integers*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData));
    }

    /**
     * testVerifyGetLocalCodesArrayHasUniqueValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalCodesArrayHasUniqueIntegerValues
     */
    public function testVerifyGetLocalCodesArrayHasUniqueValues(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\codesNotUnique\_pvcXData();
        self::expectOutputRegex('/not all exception codes are unique*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData));
    }

    /**
     * testVerifyGetMessagesArrayHasStringValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalMessagesArrayHasStringsForValues
     */
    public function testVerifyGetMessagesArrayHasStringValues(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\messagesNotStrings\_pvcXData();
        self::expectOutputRegex('/not all exception messages are strings.*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalMessagesArrayHasStringsForValues($xData));
    }

    /**
     * testVerifyLibrary
     * @covers \pvc\err\XDataTestMaster::verifylibrary
     */
    public function testVerifyLibrary(): void
    {
        $xData = new _pvcXData();
        self::assertTrue($this->xDataTestMaster->verifylibrary($xData));
    }
}
