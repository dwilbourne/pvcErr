<?php

declare(strict_types=1);

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err;

use PHPUnit\Framework\TestCase;
use pvc\err\XLibUtils;
use pvc\interfaces\err\XDataInterface;

class XDataTestMaster extends TestCase
{
    /**
     * @var array<int, class-string>
     */
    private array $exceptionClassStrings;

    /**
     * @var XDataInterface
     */
    private XDataInterface $libraryData;


    /**
     * verifylibrary
     * @param class-string $xDataClassString
     */
    public function verifylibrary(string $xDataClassString): void
    {
        /** @var XDataInterface $object */
        $object = new $xDataClassString();;
        $this->libraryData = $object;
        $this->exceptionClassStrings = $this->getExceptionClassStringsFromDir();
        $this->verifyGetLocalCodesKeysMatchClassStringsFromDir();
        $this->verifyGetLocalMessagesKeysMatchClassStringsFromDir();
        $this->verifyGetLocalCodesArrayHasUniqueIntegerValues();
        $this->verifyGetLocalMessagesArrayHasStringsForValues();
        $this->verifyGetNamespaceReturnsString();
        $this->verifyGetDirectoryReturnsDirectory();
    }

    /**
     * getExceptionClassStringsFromDir
     * @return array<int, class-string>
     */
    protected function getExceptionClassStringsFromDir(): array
    {
        /**
         * initialize the private array
         */
        $classStrings = [];
        $dir = $this->libraryData->getDirectory();

        /**
         * put all the files from the directory into an array (other than . and .. )
         */
        if (false === ($files = scandir($dir))) {
            return $classStrings;
        }
        $files = array_diff($files, array('..', '.'));

        foreach ($files as $file) {
            /**
             * get the class string by parsing the file
             * @var class-string $classString
             */
            $classString = XLibUtils::getClassStringFromFile($dir . '/' . $file);

            /**
             * validate the class string:  must be reflectable (i.e. an object) and must implement Throwable.  If
             * it is valid, add it to our array of exceptions in the library
             */
            if (XLibUtils::validateExceptionClassString($classString)) {
                $classStrings[] = $classString;
            }
        }

        return $classStrings;
    }

    public function verifyGetLocalCodesKeysMatchClassStringsFromDir(): void
    {
        $keys = array_keys($this->libraryData->getLocalXCodes());
        self::assertEqualsCanonicalizing($keys, $this->exceptionClassStrings);
    }

    public function verifyGetLocalMessagesKeysMatchClassStringsFromDir(): void
    {
        $keys = array_keys($this->libraryData->getLocalXMessages());
        self::assertEqualsCanonicalizing($keys, $this->exceptionClassStrings);
    }

    public function verifyGetLocalCodesArrayHasUniqueIntegerValues(): void
    {
        $codes = $this->libraryData->getLocalXCodes();

        /**
         * verify that the count of unique codes equals the total count of codes
         */
        self::assertEquals(count(array_unique($codes)), count($codes));

        /**
         * verify $codes is all integers.
         * if $codes is empty, $initialValue will be false and then array_reduce returns false.
         */
        $initialValue = !empty($codes);
        $callback = function($carry, $x) { return ($carry && is_int($x)); };
        self::assertTrue(array_reduce($codes, $callback, $initialValue));
    }

    public function verifyGetLocalMessagesArrayHasStringsForValues(): void
    {
        /**
         * verify $messages is all strings
         * if $codes is empty, $initialValue will be false and then array_reduce returns false.
         */
        $messages = $this->libraryData->getLocalXMessages();
        $initialValue = !empty($messages);
        $callback = function($carry, $x) { return ($carry && is_string($x)); };
        self::assertTrue(array_reduce($messages, $callback, $initialValue));
    }

    public function verifyGetNamespaceReturnsString() : void
    {
        self::assertIsString($this->libraryData->getNamespace());
    }

    public function verifyGetDirectoryReturnsDirectory(): void
    {
        self::assertTrue(is_dir($this->libraryData->getDirectory()));
    }
}
