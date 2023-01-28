<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err;

use bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use pvc\err\err\LibraryCodeArrayElementIsInvalidException;
use pvc\err\err\LibraryCodeFileNotReadableException;
use pvc\err\err\LibraryCodeFileNotParseableJsonException;
use pvc\err\err\LibraryCodeFileNotWriteableException;
use pvc\err\err\LibraryCodeValueAlreadyInUseException;
use pvc\err\ExceptionLibraryCodePrefixes;
use pvcTests\err\fixture\MockFilesysFixture;

/**
 * Class ExceptionLibraryCodePrefixesTest
 * @runTestsInSeparateProcesses
 */
class ExceptionLibraryCodePrefixesTest extends TestCase
{
    protected MockFilesysFixture $fixture;
    protected string $libraryCodesFilename;
    protected ExceptionLibraryCodePrefixes $libraryCodes;
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
        $this->libraryCodes = new ExceptionLibraryCodePrefixes($this->mockFile);
    }

    /**
     * testFileExistsButIsNotReadable
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::parseLibraryCodePrefixesFile
     * @covers \pvc\err\ExceptionLibraryCodes::createLibraryCodesException
     * @covers \pvc\err\ExceptionLibraryCodes::getMessage
     */

    public function testFileExistsButIsNotreadable(): void
    {
        $filesys = $this->fixture->getLibraryCodesFixture();
        $mockFile = $filesys->getChild($this->libraryCodesFilename);
        $mockFile->chmod(0000);
        $this->expectException(LibraryCodeFileNotReadableException::class);
        $libraryCodes = new ExceptionLibraryCodePrefixes($mockFile->url());
    }


    /**
     * if the config file does not exist, then it will be created and the call to getCode for a legitimate class
     * string in the library should return an integer.  We are fudging this a little by supplying an argument to the
     * constructor that we know does not exist in the virtual file system, which is fractionally different from
     * calling the constructor with no argument at all.
     *
     * testCreatesDefaultFileIfNoArgumentSuppliedAndDefaultFileDoesNotExist
     * @covers \pvc\err\ExceptionLibraryCodes::parseLibraryCodePrefixesFile
     */
    public function testCreatesDefaultFileIfNoArgumentSuppliedAndDefaultFileDoesNotExist(): void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFileDoesNotExistFixture());
        self::assertIsInt($this->libraryCodes->getLibraryCodePrefix("pvcTests\err\fixture"));
    }

    /**
     * testGetFileContentsReturnsFalseProducesAnException
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::parseLibraryCodePrefixesFile
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
        $this->expectException(LibraryCodeFileNotReadableException::class);
        $this->libraryCodes = new ExceptionLibraryCodePrefixes($this->mockFile);
        uopz_unset_return('file_get_contents');
    }
    */

    /**
     * testFileDoesNotContainParseableJson
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::parseLibraryCodePrefixesFile
     */
    public function testFileDoesNotContainParseableJson() : void
    {
        $this->createMockFile($this->fixture->getLibraryCodesFileDoesNotContainParseableJsonFixture());
        $this->expectException(LibraryCodeFileNotParseableJsonException::class);
        $this->libraryCodes = new ExceptionLibraryCodePrefixes($this->mockFile);
    }

    /**
     * if the library codes array is empty, then the first value return should be the start value, i.e. 1001.
     *
     * testEmptyFileProducesEmptyArrayOfLibraryCodes
     * @covers \pvc\err\ExceptionLibraryCodes::parseLibraryCodePrefixesFile
     * @covers \pvc\err\ExceptionLibraryCodes::getLibraryCodePrefix
     * @covers \pvc\err\ExceptionLibraryCodes::getNextLibraryCodePrefix
     */
    public function testEmptyFileProducesEmptyArrayOfLibraryCodes() : void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFileDoesNotExistFixture());
        $expectedLibraryCode = 1001;
        $actualLibraryCode = $this->libraryCodes->getLibraryCodePrefix("pvcTests\err\fixture");
        self::assertEquals($expectedLibraryCode, $actualLibraryCode);
    }

    /**
     * testFileDoesNotContainAJsonArray
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::parseLibraryCodePrefixesFile
     */
    public function testCodesFileArrayHasKeyWhichIsNotAString() : void
    {
        $this->createMockFile($this->fixture->getLibraryCodesFileHasKeyWhichIsNotAStringFixture());
        $this->expectException(LibraryCodeArrayElementIsInvalidException::class);
        $this->libraryCodes = new ExceptionLibraryCodePrefixes($this->mockFile);
    }

    /**
     * testCodesFileHasValueWhichIsNotAnInteger
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::parseLibraryCodePrefixesFile
     */
    public function testCodesFileHasValueWhichIsNotAnInteger() : void
    {
        $this->createMockFile($this->fixture->getLibraryCodesFileHasValueWhichIsNotAnIntegerFixture());
        $this->expectException(LibraryCodeArrayElementIsInvalidException::class);
        $this->libraryCodes = new ExceptionLibraryCodePrefixes($this->mockFile);
    }

    /**
     * testCodesFileHasDuplicateValues
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::addLibraryCodePrefix
     */
    public function testCodesFileHasDuplicateValues() : void
    {
        $this->createMockFile($this->fixture->getLibraryCodesFileHasDuplicateValueFixture());
        $this->expectException(LibraryCodeValueAlreadyInUseException::class);
        $this->libraryCodes = new ExceptionLibraryCodePrefixes($this->mockFile);
    }

    /**
     * testProperCodesFileProducesTwoElementsInLibraryCodesArray
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::addLibraryCodePrefix
     * @covers \pvc\err\ExceptionLibraryCodes::getLibraryCodePrefixes
     */
    public function testProperCodesFileProducesTwoElementsInLibraryCodesArray() : void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFixture());
        self::assertEquals(2, count($this->libraryCodes->getLibraryCodePrefixes()));
    }

    /**
     * testGetNextLibraryCodeReturnsNextInteger
     * @param ExceptionLibraryCodePrefixes $libraryCode
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::getLibraryCodePrefix
     * @covers \pvc\err\ExceptionLibraryCodes::getNextLibraryCodePrefix
     */
    public function testGetLibraryCodeAndGetNextLibraryCode() : void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFixture());

        /**
         * verify getLibraryCodePrefix properly returns the code for an existing namespace
         */
        $expectedLibraryCode = 1002;
        $actualCode = $this->libraryCodes->getLibraryCodePrefix("pvc\\err\\stock");
        self::assertEquals($expectedLibraryCode, $actualCode);

        /**
         * fixture file that exists in a different namespace than the namespaces already existing in the
         * libraryCodesFile and the internal array, so it should be added
         */
        $expectedNextLibraryCode = 1003;
        $newCode = $this->libraryCodes->getLibraryCodePrefix("pvcTests\\err\\fixture");
        self::assertEquals($expectedNextLibraryCode, $newCode);

        /**
         * verify that the internal array now has three namespace pairs in it
         */
        self::assertEquals(3, count($this->libraryCodes->getLibraryCodePrefixes()));

        /**
         * verify that the information was written back to the library file
         */
        $newLibraryCodes = new ExceptionLibraryCodePrefixes($this->mockFile);
        self::assertEquals(3, count($newLibraryCodes->getLibraryCodePrefixes()));
    }

    /**
     * testGetLibraryCodeFailsIfFileIsNotWriteable
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::getLibraryCodePrefix
     */
    public function testGetLibraryCodeFailsIfFileIsNotWriteable(): void
    {
        $this->createLibraryCodes($this->fixture->getLibraryCodesFixture());

        $filesys = $this->fixture->getLibraryCodesFixture();
        $mockFile = $filesys->getChild($this->libraryCodesFilename);
        /**
         * no permissions for anyone, i.e. not writeable
         */
        $mockFile->chmod(0000);
        $this->expectException(LibraryCodeFileNotWriteableException::class);

        $newCode = $this->libraryCodes->getLibraryCodePrefix("pvcTests\\err\\fixture");
    }
}
