<?php

declare(strict_types=1);

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err;

use PHPUnit\Framework\MockObject\MockObject;
use pvc\err\err\XFactoryClassStringArgumentException;
use pvc\err\err\XFactoryMissingXDataException;
use pvc\err\XFactory;
use PHPUnit\Framework\TestCase;
use pvc\err\XCodePrefixes;
use pvc\interfaces\err\XDataInterface;
use pvc\interfaces\err\XCodePrefixesInterface;
use pvcTests\err\fixture\SampleException;
use pvcTests\err\fixture\SampleNonException;
use pvcTests\err\fixtureWithoutExceptionData\UnfinishedException;

class XFactoryTest extends TestCase
{
    /**
     * @var XFactory&MockObject
     */
    protected XFactory&MockObject $factory;

    /**
     * @var XCodePrefixesInterface&MockObject
     */
    protected XCodePrefixesInterface&MockObject $mockLibraryCodes;

    /**
     * @var XDataInterface&MockObject
     */
    protected XDataInterface&MockObject $mockExceptionData;

    /**
     * @var string
     */
    protected string $messageParameter = "foo";

    public function setup(): void
    {
        $this->mockLibraryCodes = $this->createMock(XCodePrefixes::class);
        $this->mockExceptionData = $this->createMock(XDataInterface::class);
        $this->factory = $this->getMockForAbstractClass(XFactory::class, [$this->mockLibraryCodes]);
    }

    /**
     * testConstruct
     * @covers \pvc\err\XFactory::__construct
     */
    public function testConstruct(): void
    {
        $factory = new XFactory($this->mockLibraryCodes);
        self::assertInstanceOf(XFactory::class, $factory);
    }

    /**
     * testCreateExceptionThrowsExceptionIfClassStringIsInvalid
     * @throws \Throwable
     * @covers \pvc\err\XFactory::createException
     */
    public function testCreateExceptionThrowsExceptionIfClassStringIsNotReflectable() : void
    {
        /** @var class-string $badClassString */
        $badClassString = 'foo';
        $this->expectException(XFactoryClassStringArgumentException::class);
        $this->factory->createException($badClassString);
    }

    /**
     * testCreateExceptionThrowsExceptionIfClassStringDoesNotImplementThrowable
     * @throws \Throwable
     * @covers \pvc\err\XFactory::createException
     * @covers \pvc\err\XLibUtils::validateExceptionClassString
     */
    public function testCreateExceptionThrowsExceptionIfClassStringDoesNotImplementThrowable() : void
    {
        $this->expectException(XFactoryClassStringArgumentException::class);
        $this->factory->createException(SampleNonException::class);
    }

    /**
     * testCreateExceptionRegistersNewLibraryDataObjectOnTheFly
     * @covers \pvc\err\XFactory::registerXData()
     * @covers \pvc\err\XFactory::getExceptionLibraryData
     */
    public function testRegisterLibraryDataObject(): void
    {
        self::assertEquals(0, count($this->factory->getExceptionLibraryData()));
        $this->mockExceptionData->method('getNamespace')->willReturn('pvc\err\pvc');
        $this->factory->registerXData($this->mockExceptionData);
        self::assertEquals(1, count($this->factory->getExceptionLibraryData()));
        /**
         * re-registering the same object does not add it a second time
         *
         */
        $this->factory->registerXData($this->mockExceptionData);
        self::assertEquals(1, count($this->factory->getExceptionLibraryData()));
    }

    /**
     * testCreateExceptionDiscoversNewLibraryFromExceptionClassString
     * @throws \Throwable
     * @covers \pvc\err\XFactory::discoverXDataFromClassString
     */
    public function testCreateExceptionDiscoversNewLibraryFromExceptionClassString() : void
    {
        /**
         * not relevent to this test, but the non-namespaced class needs to be loaded manually otherwise it cannot be
         * reflected
         */
        require_once 'tests' . DIRECTORY_SEPARATOR . 'fixture' . DIRECTORY_SEPARATOR . 'ClassWithNoNamespace.php';
        self::assertEquals(0, count($this->factory->getExceptionLibraryData()));
        $this->factory->createException(SampleException::class, ['foo']);
        self::assertEquals(1, count($this->factory->getExceptionLibraryData()));
    }

    /**
     * testCreateExceptionThrowsExceptionWhenThereIsNoExceptionLibraryData
     * @throws \Throwable
     * @covers \pvc\err\XFactory::getXDataFor
     * @covers \pvc\err\XFactory::discoverXDataFromClassString
     */
    public function testCreateExceptionThrowsExceptionWhenThereIsNoExceptionLibraryData(): void
    {
        $this->expectException(XFactoryMissingXDataException::class);
        $this->factory->createException(UnfinishedException::class);
    }

    /**
     * testNonScalarParamsConvertedToTypeInMessage
     * @throws \Throwable
     * @covers \pvc\err\XFactory::createException
     * @covers \pvc\err\XFactory::getXDataFor
     */
    public function testNonScalarParamsConvertedToTypeInMessage() : void
    {
        $localCode = 1000;
        $localMessage =  "Invalid value (%s) supplied.";
        /**
         * have to use the namespace for the sample exception.  The createException method grabs the namespace of the
         * exception via reflection in order to get the library data.
         */
        $fixtureNamespace = 'pvcTests\err\fixture';

        $this->mockExceptionData->method('getLocalXCode')->willReturn($localCode);
        $this->mockExceptionData->method('getLocalXMessage')->willReturn($localMessage);
        $this->mockExceptionData->method('getNamespace')->willReturn($fixtureNamespace);
        $this->factory->registerXData($this->mockExceptionData);

        $this->mockLibraryCodes->method('getXCodePrefix')->willReturn(2000);

        $exception = $this->factory->createException(SampleException::class, [new \stdClass()]);
        self::assertStringContainsString("<object>", $exception->getMessage());
    }

    /**
     * testNonZeroLocalCodeResultsInFullExceptionCodeGeneration
     * @throws \Throwable
     * @covers \pvc\err\XFactory::createException
     */
    public function testNonZeroLocalCodeResultsInFullExceptionCodeGeneration() : void
    {
        $localCode = 1000;
        $localMessage = "Invalid value (%s) supplied.";
        /**
         * have to use the namespace for the sample exception.  The createException method grabs the namespace of the
         * exception via reflection in order to get the library data.
         */
        $fixtureNamespace = 'pvcTests\err\fixture';

        $this->mockExceptionData->method('getLocalXCode')->willReturn($localCode);
        $this->mockExceptionData->method('getLocalXMessage')->willReturn($localMessage);
        $this->mockExceptionData->method('getNamespace')->willReturn($fixtureNamespace);
        $this->factory->registerXData($this->mockExceptionData);

        $this->mockLibraryCodes->method('getXCodePrefix')->willReturn(2000);

        $exception = $this->factory->createException(SampleException::class, [new \stdClass()]);
        self::assertEquals(20001000, $exception->getCode());
    }

    /**
     * testLocalCodeEqualToZeroResultsInExceptionCodeOfZero
     * @throws \Throwable
     * @covers \pvc\err\XFactory::createException
     */
    public function testLocalCodeEqualToZeroResultsInExceptionCodeOfZero() : void
    {
        $localCode = 0;
        $localMessage = "Invalid value (%s) supplied.";
        $fixtureNamespace = 'pvcTests\err\fixture';

        $this->mockExceptionData->method('getLocalXCode')->willReturn($localCode);
        $this->mockExceptionData->method('getLocalXMessage')->willReturn($localMessage);
        $this->mockExceptionData->method('getNamespace')->willReturn($fixtureNamespace);
        $this->factory->registerXData($this->mockExceptionData);

        $exception = $this->factory->createException(SampleException::class, [new \stdClass()]);
        self::assertEquals(0, $exception->getCode());
    }

}
