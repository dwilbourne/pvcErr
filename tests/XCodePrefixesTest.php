<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err;

use bovigo\vfs\vfsStreamContent;
use bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use pvc\err\err\XCodePrefixesFileNotReadableWriteableException;
use pvc\err\err\XCodePrefixesFileNotParseableJsonException;
use pvc\err\err\XCodePrefixAlreadyInUseException;
use pvc\err\XCodePrefixes;
use pvcTests\err\fixture\MockFilesysFixture;

/**
 * Class XCodePrefixesTest
 * @runTestsInSeparateProcesses
 */
class XCodePrefixesTest extends TestCase
{
    protected MockFilesysFixture $fixture;
    protected string $libraryCodesFilename;
    protected XCodePrefixes $libraryCodes;
    protected string $mockFile;

    public function setUp(): void
    {
        $this->fixture = new MockFilesysFixture();
        $this->libraryCodesFilename = $this->fixture->getJsonFileName();
    }

    protected function createMockFile(vfsStreamDirectory $filesys) : void
    {
        $this->mockFile = $filesys->url() . "/" . $this->libraryCodesFilename;
    }

    protected function createLibraryCodes(vfsStreamDirectory $filesys) : void
    {
        $this->createMockFile($filesys);
        $this->libraryCodes = new XCodePrefixes($this->mockFile);
    }

    /**
     * testFileExistsButIsNotReadable
     * @throws \Throwable
     * @covers \pvc\err\XCodePrefixes::parsePrefixesFile
     * @covers \pvc\err\XCodePrefixes::createPrefixingException
     * @covers \pvc\err\XCodePrefixes::getCode
     * @covers \pvc\err\XCodePrefixes::getMessage
     */

    public function testFileExistsButIsNotreadable(): void
    {
        $filesys = $this->fixture->getLibraryCodesFixture();
        /** @var vfsStreamContent $mockFile */
        $mockFile = $filesys->getChild($this->libraryCodesFilename);
        $mockFile->chmod(0000);
        $this->expectException(XCodePrefixesFileNotReadableWriteableException::class);
        $libraryCodes = new XCodePrefixes($mockFile->url());
    }


    /**
     * if the config file does not exist, then it will be created and the call to getCode for a legitimate class
     * string in the library should return an integer.  We are fudging this a little by supplying an argument to the
     * constructor that we know does not exist in the virtual file system, which is fractionally different from
     * calling the constructor with no argument at all.
     *
     * testCreatesDefaultFileIfNoArgumentSuppliedAndDefaultFileDoesNotExist
     * @covers \pvc\err\XCodePrefixes::parsePrefixesFile
     */
    public function testCreatesDefaultFileIfNoArgumentSuppliedAndDefaultFileDoesNotExist(): void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFileDoesNotExistFixture());
        self::assertIsInt($this->libraryCodes->getXCodePrefix("pvcTests\err\fixture"));
    }

    /**
     * testGetFileContentsReturnsFalseProducesAnException
     * @throws \Throwable
     * @covers \pvc\err\XCodePrefixes::parsePrefixesFile
     */

    /*
     * You can uncomment this test and run it individually.  But leaving it in the test suite messes up phpunit's
     * routine for generating code coverage because that code uses the file_get_contents function, which is
     * purposefully being modified for this test......
     */

    /*
    public function testGetFileContentsReturnsFalseProducesAnException() : void
    {
        uopz_set_return('file_get_contents', false);
        $this->createMockFile($this->fixture->getLibraryCodesFixture());
        $this->expectException(XCodePrefixesFileNotReadableWriteableException::class);
        $this->xPrefixes = new XCodePrefixes($this->mockFile);
        uopz_unset_return('file_get_contents');
    }
    */

    /**
     * testFileDoesNotContainParseableJson
     * @throws \Throwable
     * @covers \pvc\err\XCodePrefixes::parsePrefixesFile
     */
    public function testFileDoesNotContainParseableJson() : void
    {
        $this->createMockFile($this->fixture->getLibraryCodesFileDoesNotContainParseableJsonFixture());
        $this->expectException(XCodePrefixesFileNotParseableJsonException::class);
        $this->libraryCodes = new XCodePrefixes($this->mockFile);
    }

    /**
     * if the library codes array is empty, then the first value return should be the start value, i.e. 1001.
     *
     * testEmptyFileProducesEmptyArrayOfLibraryCodes
     * @covers \pvc\err\XCodePrefixes::parsePrefixesFile
     * @covers \pvc\err\XCodePrefixes::getXCodePrefix
     * @covers \pvc\err\XCodePrefixes::getNextXCodePrefix
     */
    public function testEmptyFileProducesEmptyArrayOfLibraryCodes() : void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFileDoesNotExistFixture());
        $expectedLibraryCode = 1001;
        $actualLibraryCode = $this->libraryCodes->getXCodePrefix("pvcTests\err\fixture");
        self::assertEquals($expectedLibraryCode, $actualLibraryCode);
    }

    /**
     * testCodesFileHasDuplicateValues
     * @throws \Throwable
     * @covers \pvc\err\XCodePrefixes::addPrefix
     */
    public function testCodesFileHasDuplicateValues() : void
    {
        $this->createMockFile($this->fixture->getLibraryCodesFileHasDuplicateValueFixture());
        $this->expectException(XCodePrefixAlreadyInUseException::class);
        $this->libraryCodes = new XCodePrefixes($this->mockFile);
    }

    /**
     * testProperCodesFileProducesTwoElementsInLibraryCodesArray
     * @throws \Throwable
     * @covers \pvc\err\XCodePrefixes::__construct
     * @covers \pvc\err\XCodePrefixes::parsePrefixesFile
     * @covers \pvc\err\XCodePrefixes::addPrefix
     * @covers \pvc\err\XCodePrefixes::getXCodePrefixes
     */
    public function testProperCodesFileProducesTwoElementsInLibraryCodesArray() : void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFixture());
        self::assertEquals(2, count($this->libraryCodes->getXCodePrefixes()));
    }

    /**
     * testGetLibraryCodeAndGetNextLibraryCode
     * @throws \Throwable
     * @covers \pvc\err\XCodePrefixes::getXCodePrefix
     * @covers \pvc\err\XCodePrefixes::getNextXCodePrefix
     */
    public function testGetLibraryCodeAndGetNextLibraryCode() : void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFixture());

        /**
         * verify getXCodePrefix properly returns the code for an existing namespace
         */
        $expectedLibraryCode = 1002;
        $actualCode = $this->libraryCodes->getXCodePrefix("pvc\\err\\stock");
        self::assertEquals($expectedLibraryCode, $actualCode);

        /**
         * fixture file that exists in a different namespace than the namespaces already existing in the
         * libraryCodesFile and the internal array, so it should be added
         */
        $expectedNextLibraryCode = 1003;
        $newCode = $this->libraryCodes->getXCodePrefix("pvcTests\\err\\fixture");
        self::assertEquals($expectedNextLibraryCode, $newCode);

        /**
         * verify that the internal array now has three namespace pairs in it
         */
        self::assertEquals(3, count($this->libraryCodes->getXCodePrefixes()));

        /**
         * verify that the information was written back to the library file
         */
        $newLibraryCodes = new XCodePrefixes($this->mockFile);
        self::assertEquals(3, count($newLibraryCodes->getXCodePrefixes()));
    }

    /**
     * testGetLibraryCodeFailsIfFileIsNotWriteable
     * @throws \Throwable
     * @covers \pvc\err\XCodePrefixes::getXCodePrefix
     */
    public function testGetLibraryCodeFailsIfFileIsNotWriteable(): void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFixture());

        $filesys = $this->fixture->getLibraryCodesFixture();
        /** @var vfsStreamContent $mockFile */
        $mockFile = $filesys->getChild($this->libraryCodesFilename);
        /**
         * no permissions for anyone, i.e. not writeable
         */
        $mockFile->chmod(0000);
        $this->expectException(XCodePrefixesFileNotReadableWriteableException::class);

        $newCode = $this->libraryCodes->getXCodePrefix("pvcTests\\err\\fixture");
    }
}
