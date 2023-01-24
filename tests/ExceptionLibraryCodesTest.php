<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare (strict_types=1);

namespace pvcTests\err;

use PHPUnit\Framework\TestCase;
use pvc\err\err\LibraryCodeFileNotWriteableException;
use pvc\err\ExceptionLibraryCodes;
use pvcTests\err\fixture\MockFilesysFixture;

class ExceptionLibraryCodesTest extends TestCase
{
    protected MockFilesysFixture $fixture;
    protected string $libraryCodesFilename;

	public function setUp() : void
	{
        $this->fixture = new MockFilesysFixture();
        $this->libraryCodesFilename = $this->fixture->getJsonFileName();
	}

    /**
     * testFileExistsButIsNotWriteable
     * @throws \Throwable
     * @covers \pvc\err\ExceptionLibraryCodes::parseLibraryCodesFile
     */
    public function testFileExistsButIsNotWriteable() : void
    {
        $filesys = $this->fixture->getLibraryCodesFileDoesExistButIsNotWriteableFixture();
        $mockFile = $filesys->getChild($this->libraryCodesFilename);
        $this->expectException(LibraryCodeFileNotWriteableException::class);
        $libraryCodes = new ExceptionLibraryCodes($mockFile);
    }

    public function testCreatesDefaultFileIfNoArgumentSuppliedAndDefaultFileDoesNotExist() : void
    {

    }



}
