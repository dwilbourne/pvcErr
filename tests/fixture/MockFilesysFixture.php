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
    protected string $jsonFileName = "libraryCodePrefixes.json";
    protected string $root = 'root';
    protected $perms = null;

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
        return vfsStream::setup();
    }

    /**
     * @function getLibraryCodesFileDoesNotContainParseableJsonFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileDoesNotContainParseableJsonFixture(): vfsStreamDirectory
    {
        $notJson = 'boog a lee boo';
        $filesArray = [$this->jsonFileName => $notJson];
        return vfsStream::setup($this->root, $this->perms, $filesArray);
    }

    /**
     * @function getLibraryCodesFileHasKeyWhichIsNotAStringFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileHasKeyWhichIsNotAStringFixture(): vfsStreamDirectory
    {
        $jsonArrayHasKeyWhichIsNotAString = '{ "4" : 7, "foo" : "bar" }';
        $filesArray = [$this->jsonFileName => $jsonArrayHasKeyWhichIsNotAString];
        return vfsStream::setup($this->root, $this->perms, $filesArray);
    }

    /**
     * @function getLibraryCodesFileHasValueWhichIsNotAnIntegerFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileHasValueWhichIsNotAnIntegerFixture(): vfsStreamDirectory
    {
        $jsonArrayHasValueWhichIsNotAnInteger = '{ "foo" : 7, "bar" : "baz" }';
        $filesArray = [$this->jsonFileName => $jsonArrayHasValueWhichIsNotAnInteger];
        return vfsStream::setup($this->root, $this->perms, $filesArray);
    }

    /**
     * @function getLibraryCodesFileHasDuplicateValueFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFileHasDuplicateValueFixture(): vfsStreamDirectory
    {
        $jsonArrarHasDuplicateValue = '{ "foo" : 7, "bar" : 7 }';
        $filesArray = [$this->jsonFileName => $jsonArrarHasDuplicateValue];
        return vfsStream::setup($this->root, $this->perms, $filesArray);
    }

    /**
     * @function getLibraryCodesFixture
     * @return vfsStreamDirectory
     */
    public function getLibraryCodesFixture(): vfsStreamDirectory
    {
        $jsonArray = '{ "pvc\\\\err\\\\pvc" : 1001, "pvc\\\\err\\\\stock" : 1002 }';
        $filesArray = [$this->jsonFileName => $jsonArray];
        return vfsStream::setup($this->root, $this->perms, $filesArray);
    }
}