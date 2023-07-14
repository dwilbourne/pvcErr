<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err;

use PHPUnit\Framework\TestCase;
use pvc\err\XCodePrefixes;

/**
 * Class XCodePrefixesTest
 * @runTestsInSeparateProcesses
 */
class XCodePrefixesTest extends TestCase
{
    /**
     * @var string
     */
    protected string $fixtureDir;

    public function setUp(): void
    {
        $this->fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtureForXCodePrefixes';
    }

    /**
     * @function testGetPrefixes
     * @covers \pvc\err\XCodePrefixes::getPvcXCodePrefixes
     */
    public function testGetPvcXCodePrefixes(): void
    {
        $array = XCodePrefixes::getPvcXCodePrefixes();
        self::assertIsArray($array);
        foreach ($array as $namespace => $prefix) {
            self::assertIsString($namespace);
            self::assertIsInt($prefix);
        }
    }

    /**
     * @function testGetPrefixWithValidNamespace
     * @covers \pvc\err\XCodePrefixes::getXCodePrefix
     */
    public function testGetPrefixWithValidNamespace(): void
    {
        $testNamespace = 'pvc\err\pvc';
        $prefix = XCodePrefixes::getXCodePrefix($testNamespace);
        self::assertIsInt($prefix);
        self::assertTrue(0 < XCodePrefixes::getXCodePrefix($testNamespace));
    }

    /**
     * @function testGetPrefixReturnsZeroWithInvalidNamespace
     * @covers \pvc\err\XCodePrefixes::getXCodePrefix
     */
    public function testGetPrefixReturnsZeroWithInvalidNamespace(): void
    {
        $testNamespace = 'foo';
        self::assertEquals(0, XCodePrefixes::getXCodePrefix($testNamespace));
    }

    /**
     * @function testGetExternalXCodePrefixesIgnoresEnvVariableIfVariableValueIsInvalid
     * @covers \pvc\err\XCodePrefixes::getExternalXCodePrefixes
     */
    public function testGetExternalXCodePrefixesIgnoresEnvVariableIfVariableValueIsInvalid(): void
    {
        /**
         * environment variable name is correct but path is nonsense
         */
        putenv('XCodePrefixes=/some/nonexistent/path');
        $result = XCodePrefixes::getExternalXCodePrefixes();
        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    /**
     * @function testGetExternalXCodePrefixesIgnoresBadFileContents
     * @covers \pvc\err\XCodePrefixes::getExternalXCodePrefixes
     */
    public function testGetExternalXCodePrefixesIgnoresBadFileContents(): void
    {
        /**
         * environment variable name points to unparseable php file
         */
        $badFileName = $this->fixtureDir . DIRECTORY_SEPARATOR . 'UnparseableFile.php';
        putenv("XCodePrefixes=$badFileName");
        $result = XCodePrefixes::getExternalXCodePrefixes();
        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    /**
     * @function testGetExternalXCodePrefixesGetsArrayContents
     * @covers \pvc\err\XCodePrefixes::getExternalXCodePrefixes
     */
    public function testGetExternalXCodePrefixesGetsArrayContents(): void
    {
        $goodFileName = $this->fixtureDir . DIRECTORY_SEPARATOR . 'XCodePrefixes.php';
        putenv("XCodePrefixes=$goodFileName");
        /**
         * there is one prefix in the test fixture file
         */
        $result = XCodePrefixes::getExternalXCodePrefixes();
        self::assertEquals(1, count($result));
        foreach ($result as $namespace => $prefix) {
            self::assertIsString($namespace);
            self::assertIsInt($prefix);
            self::assertTrue($prefix >= XCodePrefixes::MIN_APPLICATION_PREFIX);
        }
    }

    /**
     * @function testGetXCodePrefixes
     * @covers \pvc\err\XCodePrefixes::getXCodePrefixes
     */
    public function testGetXCodePrefixes(): void
    {
        $goodFileName = $this->fixtureDir . DIRECTORY_SEPARATOR . 'XCodePrefixes.php';
        putenv("XCodePrefixes=$goodFileName");
        $externalXCodePrefixes = XCodePrefixes::getExternalXCodePrefixes();
        $pvcXCodePrefixes = XCodePrefixes::getPvcXCodePrefixes();
        $expectedTotalPrefixes = count($externalXCodePrefixes) + count($pvcXCodePrefixes);
        self::assertEquals($expectedTotalPrefixes, count(XCodePrefixes::getXCodePrefixes()));
    }
}
