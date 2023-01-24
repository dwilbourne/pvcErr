<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare (strict_types=1);

namespace pvcTests\err\fixture;

use bovigo\vfs\vfsStream;
use bovigo\vfs\vfsStreamDirectory;

/**
 * Class MockFilesysFixture
 */
class MockFilesysFixture
{
    protected string $jsonFileName = "libraryCodes.json";

    protected vfsStreamDirectory $libraryCodesFileDoesNotExistFixture;

    protected vfsStreamDirectory $libraryCodesFileDoesExistButIsNotWriteableFixture;

    protected vfsStreamDirectory $libraryCodesFileDoesNotContainParseableJsonFixture;

    protected vfsStreamDirectory $libraryCodesFileDoesNotReturnAnArryFixture;

    protected vfsStreamDirectory $libraryCodesFileHasKeyWhichIsNotAStringFixture;

    protected vfsStreamDirectory $libraryCodesFileHasValueWhichIsNotAnIntegerFixture;

    protected vfsStreamDirectory $libraryCodesFileHasDuplicateValueFixture;

    protected vfsStreamDirectory $libraryCodesFixture;

    public function __construct()
    {
        $root = 'root';
        $perms = null;

        $this->libraryCodesFileDoesNotExistFixture = vfsStream::setup();

        $this->libraryCodesFileDoesExistButIsNotWriteableFixture = vfsStream::setup();
        $this->libraryCodesFileDoesExistButIsNotWriteableFixture->chmod(0000);

        $notJson = "boog a lee boo";
        $filesArray = [$this->jsonFileName => $notJson];
        $this->libraryCodesFileDoesNotContainParseableJsonFixture = vfsStream::setup($root, $perms, $filesArray);

        $notJsonArray = "{ 'name' : 'John' }";
        $filesArray = [$this->jsonFileName => $notJsonArray];
        $this->libraryCodesFileDoesNotReturnAnArryFixture = vfsStream::setup($root, $perms, $filesArray);

        $jsonArrayHasKeyWhichIsNotAString = "[ { 4 : 7, 'foo' : 'bar' } ]";
        $filesArray = [$this->jsonFileName => $jsonArrayHasKeyWhichIsNotAString];
        $this->libraryCodesFileHasKeyWhichIsNotAStringFixture = vfsStream::setup($root, $perms, $filesArray);

        $jsonArrayHasValueWhichIsNotAnInteger = "[ { 'foo' : 7, 'bar' : 'baz' } ]";
        $filesArray = [$this->jsonFileName => $jsonArrayHasValueWhichIsNotAnInteger];
        $this->libraryCodesFileHasValueWhichIsNotAnIntegerFixture = vfsStream::setup($root, $perms, $filesArray);

        $jsonArrarHasDuplicateValue = "[ { 'foo' : 7, 'bar' : 7 } ]";
        $filesArray = [$this->jsonFileName => $jsonArrarHasDuplicateValue];
        $this->libraryCodesFileHasDuplicateValueFixture = vfsStream::setup($root, $perms, $filesArray);

        $jsonArray = "[ { 'pvc\\Err\\pvc' : 1001, 'pvc\\Err\\stock' : 1002} ]";
        $filesArray = [$this->jsonFileName => $jsonArray];
        $this->libraryCodesFixture = vfsStream::setup($root, $perms, $filesArray);
    }

    /**
     * @function getJsonFileName
     * @return string
     */
    public function getJsonFileName(): string
    {
        return $this->jsonFileName;
    }

    /**
     * @function getLibraryCodesFileDoesNotExistFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileDoesNotExistFixture(): vfsStreamDirectory
    {
        return $this->libraryCodesFileDoesNotExistFixture;
    }

    /**
     * @function getLibraryCodesFileDoesExistButIsNotWriteableFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileDoesExistButIsNotWriteableFixture(): vfsStreamDirectory
    {
        return $this->libraryCodesFileDoesExistButIsNotWriteableFixture;
    }

    /**
     * @function getLibraryCodesFileDoesNotContainParseableJsonFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileDoesNotContainParseableJsonFixture(): vfsStreamDirectory
    {
        return $this->libraryCodesFileDoesNotContainParseableJsonFixture;
    }

    /**
     * @function getLibraryCodesFileDoesNotReturnAnArryFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileDoesNotReturnAnArryFixture(): vfsStreamDirectory
    {
        return $this->libraryCodesFileDoesNotReturnAnArryFixture;
    }

    /**
     * @function getLibraryCodesFileHasKeyWhichIsNotAStringFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileHasKeyWhichIsNotAStringFixture(): vfsStreamDirectory
    {
        return $this->libraryCodesFileHasKeyWhichIsNotAStringFixture;
    }

    /**
     * @function getLibraryCodesFileHasValueWhichIsNotAnIntegerFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileHasValueWhichIsNotAnIntegerFixture(): vfsStreamDirectory
    {
        return $this->libraryCodesFileHasValueWhichIsNotAnIntegerFixture;
    }

    /**
     * @function getLibraryCodesFileHasDuplicateValueFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileHasDuplicateValueFixture(): vfsStreamDirectory
    {
        return $this->libraryCodesFileHasDuplicateValueFixture;
    }

    /**
     * @function getLibraryCodesFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFixture(): vfsStreamDirectory
    {
        return $this->libraryCodesFixture;
    }


}