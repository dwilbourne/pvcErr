<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err;

use PHPUnit\Framework\TestCase;
use pvc\err\err\InvalidXCodePrefixNumberException;
use pvc\err\XCodePrefixes;

class XCodePrefixesTest extends TestCase
{
    protected string $nameSpace;

    public function setUp(): void
    {
        /**
         * for testing purposes it does not matter whether this is a valid namespace name or not
         */
        $this->nameSpace = 'foo';
    }

    /**
     * testAddXCodePrefixThrowsExceptionWithInvalidPrefixNumber
     * @throws InvalidXCodePrefixNumberException
     * @covers \pvc\err\XCodePrefixes::addXCodePrefix
     */
    public function testAddXCodePrefixThrowsExceptionWithInvalidPrefixNumber(): void
    {
        /**
         * minimum prefix code is 1000
         */
        $badPrefix = 955;
        self::expectException(InvalidXCodePrefixNumberException::class);
        XCodePrefixes::addXCodePrefix($this->nameSpace, $badPrefix);
    }

    /**
     * testAddXCodePrefixThrowsExceptionWithDuplicatePrefixCode
     * @throws InvalidXCodePrefixNumberException
     * @covers \pvc\err\XCodePrefixes::addXCodePrefix
     */
    public function testAddXCodePrefixThrowsExceptionWithDuplicatePrefixCode(): void
    {
        $prefix = 1020;
        XCodePrefixes::addXCodePrefix($this->nameSpace, $prefix);
        self::expectException(InvalidXCodePrefixNumberException::class);
        XCodePrefixes::addXCodePrefix($this->nameSpace, $prefix);
    }

    /**
     * testAddGetXCodePrefix
     * @throws InvalidXCodePrefixNumberException
     * @covers \pvc\err\XCodePrefixes::addXCodePrefix
     * @covers \pvc\err\XCodePrefixes::getXCodePrefix
     */
    public function testAddGetXCodePrefix(): void
    {
        /**
         * the protected $prefixes array in XCodePrefixes is static.  So you have to use a different prefix in each
         * addition, even between tests, because the static array persists between tests!
         */
        $prefix = 1021;
        XCodePrefixes::addXCodePrefix($this->nameSpace, $prefix);
        self::assertEquals($prefix, XCodePrefixes::getXCodePrefix($this->nameSpace));
    }

    /**
     * testGetXCodePrefixes
     * @covers \pvc\err\XCodePrefixes::getXCodePrefixes
     */
    public function testGetXCodePrefixes(): void
    {
        self::assertIsArray(XCodePrefixes::getXCodePrefixes());
    }
}
