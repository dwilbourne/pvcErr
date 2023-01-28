<?php

declare(strict_types=1);

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err;

use pvc\err\err\ExceptionFactoryArgumentException;
use pvc\err\err\ExceptionFactoryMissingLibraryException;
use pvc\err\ExceptionFactory;
use PHPUnit\Framework\TestCase;
use pvc\err\ExceptionLibraryCodePrefixes;
use pvc\interfaces\err\ExceptionLibraryDataInterface;
use pvc\interfaces\err\ExceptionLibraryCodePrefixesInterface;
use pvcTests\err\fixture\SampleException;
use pvcTests\err\fixture\SampleNonException;
use pvcTests\err\fixtureWithoutExceptionData\UnfinishedException;

class ExceptionFactoryTest extends TestCase
{
    protected ExceptionFactory $factory;
    protected ExceptionLibraryCodePrefixesInterface $mockLibraryCodes;
    protected ExceptionLibraryDataInterface $mockExceptionData;
    protected string $messageParameter = "foo";

    public function setup(): void
    {
        $this->mockLibraryCodes = $this->createStub(ExceptionLibraryCodePrefixes::class);
        $this->mockExceptionData = $this->createStub(ExceptionLibraryDataInterface::class);
        $this->factory = $this->getMockForAbstractClass(ExceptionFactory::class, [$this->mockLibraryCodes]);
    }

    /**
     * testCreateExceptionThrowsExceptionIfClassStringIsInvalid
     * @throws \Throwable
     * @covers \pvc\err\ExceptionFactory::createException
     * @covers \pvc\err\ExceptionFactory::validateClassString
     * @covers \pvc\err\ExceptionFactory::createExceptionFactoryException
     * @covers \pvc\err\ExceptionFactory::getCode
     * @covers \pvc\err\ExceptionFactory::getMessage
     */
    public function testCreateExceptionThrowsExceptionIfClassStringIsNotReflectable() : void
    {
        $this->expectException(ExceptionFactoryArgumentException::class);
        $this->factory->createException("foo");
    }

    /**
     * testCreateExceptionThrowsExceptionIfClassStringDoesNotImplementThrowable
     * @throws \Throwable
     * @covers \pvc\err\ExceptionFactory::createException
     * @covers \pvc\err\ExceptionFactory::validateClassString
     * @covers \pvc\err\ExceptionFactory::createExceptionFactoryException
     * @covers \pvc\err\ExceptionFactory::getCode
     * @covers \pvc\err\ExceptionFactory::getMessage
     */
    public function testCreateExceptionThrowsExceptionIfClassStringDoesNotImplementThrowable() : void
    {
        $this->expectException(ExceptionFactoryArgumentException::class);
        $this->factory->createException(SampleNonException::class);
    }

    /**
     * testCreateExceptionRegistersNewLibraryDataObjectOnTheFly
     * @covers \pvc\err\ExceptionFactory::registerExceptionLibraryData()
     * @covers \pvc\err\ExceptionFactory::getExceptionLibraryData
     */
    public function testRegisterLibraryDataObject(): void
    {
        self::assertEquals(0, count($this->factory->getExceptionLibraryData()));
        $this->mockExceptionData->method('getNamespace')->willReturn('pvc\err\pvc');
        $this->factory->registerExceptionLibraryData($this->mockExceptionData);
        self::assertEquals(1, count($this->factory->getExceptionLibraryData()));
        /**
         * re-registering the same object does not add it a second time
         *
         */
        $this->factory->registerExceptionLibraryData($this->mockExceptionData);
        self::assertEquals(1, count($this->factory->getExceptionLibraryData()));
    }

    /**
     * testCreateExceptionDiscoversNewLibraryFromExceptionClassString
     * @throws \Throwable
     * @covers \pvc\err\ExceptionFactory::discoverLibraryDataFromClassString
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
     * @covers \pvc\err\ExceptionFactory::getLibraryDataFor
     * @covers \pvc\err\ExceptionFactory::discoverLibraryDataFromClassString
     */
    public function testCreateExceptionThrowsExceptionWhenThereIsNoExceptionLibraryData(): void
    {
        $this->expectException(ExceptionFactoryMissingLibraryException::class);
        $this->factory->createException(UnfinishedException::class);
    }

    /**
     * testNonScalarParamsConvertedToTypeInMessage
     * @throws \Throwable
     * @covers \pvc\err\ExceptionFactory::createException
     * @covers \pvc\err\ExceptionFactory::getLibraryDataFor
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

        $this->mockExceptionData->method('getLocalCode')->willReturn($localCode);
        $this->mockExceptionData->method('getLocalMessage')->willReturn($localMessage);
        $this->mockExceptionData->method('getNamespace')->willReturn($fixtureNamespace);
        $this->factory->registerExceptionLibraryData($this->mockExceptionData);

        $this->mockLibraryCodes->method('getLibraryCodePrefix')->willReturn(2000);

        $exception = $this->factory->createException(SampleException::class, [new \stdClass()]);
        self::assertStringContainsString("<object>", $exception->getMessage());
    }

    /**
     * testNonZeroLocalCodeResultsInFullExceptionCodeGeneration
     * @throws \Throwable
     * @covers \pvc\err\ExceptionFactory::createException
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

        $this->mockExceptionData->method('getLocalCode')->willReturn($localCode);
        $this->mockExceptionData->method('getLocalMessage')->willReturn($localMessage);
        $this->mockExceptionData->method('getNamespace')->willReturn($fixtureNamespace);
        $this->factory->registerExceptionLibraryData($this->mockExceptionData);

        $this->mockLibraryCodes->method('getLibraryCodePrefix')->willReturn(2000);

        $exception = $this->factory->createException(SampleException::class, [new \stdClass()]);
        self::assertEquals(20001000, $exception->getCode());
    }

    /**
     * testLocalCodeEqualToZeroResultsInExceptionCodeOfZero
     * @throws \Throwable
     * @covers \pvc\err\ExceptionFactory::createException
     */
    public function testLocalCodeEqualToZeroResultsInExceptionCodeOfZero() : void
    {
        $localCode = 0;
        $localMessage = "Invalid value (%s) supplied.";
        $fixtureNamespace = 'pvcTests\err\fixture';

        $this->mockExceptionData->method('getLocalCode')->willReturn($localCode);
        $this->mockExceptionData->method('getLocalMessage')->willReturn($localMessage);
        $this->mockExceptionData->method('getNamespace')->willReturn($fixtureNamespace);
        $this->factory->registerExceptionLibraryData($this->mockExceptionData);

        $exception = $this->factory->createException(SampleException::class, [new \stdClass()]);
        self::assertEquals(0, $exception->getCode());
    }

}
