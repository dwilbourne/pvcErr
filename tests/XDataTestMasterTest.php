<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err;

use PHPUnit\Framework\TestCase;
use pvc\err\XDataTestMaster;
use pvcTests\err\fixtureForXDataTests\SampleException;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithBadPrevParameterType;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithNonOptionalPrevParameter;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithoutPrevParameter;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithUnionTypedPrevParameter;
use pvcTests\err\fixtureForXDataTests\SampleExceptionWithUntypedPrevParameter;
use pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures\ExceptionDoesNotExtendPvcStockException;
use pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures\ExceptionWithImplicitConstructor;
use pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures\ExceptionWithNoConstructor;
use pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures\ExceptionWithNoDefaultForPrev;
use pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures\ExceptionWithNoParameters;
use pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures\ExceptionWithNoThrowableParameter;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class XDataTestMasterTest extends TestCase
{
    /**
     * @var XDataTestMaster
     */
    protected XDataTestMaster $xDataTestMaster;

    /**
     * setUp
     */
    public function setUp(): void
    {
        $this->xDataTestMaster = new XDataTestMaster();
    }

    /**
     * testVerifyGlobalPrefixCodeIsConfiguredForLibrary
     * @covers \pvc\err\XDataTestMaster::verifyGlobalPrefixCodeIsConfiguredForLibrary
     */
    public function testVerifyGlobalPrefixCodeIsConfiguredForLibrary(): void
    {
        /**
         * even though this fixture has all good data in it, it is not registered in the XCodePrefixes registry
         */
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\allGood\_pvcXData();
        self::assertFalse($this->xDataTestMaster->verifyGlobalPrefixCodeIsConfiguredForLibrary($xData));

        /**
         * this one is registered
         */
        $xData = new \pvc\err\err\_ErrXData();
        self::assertTrue($this->xDataTestMaster->verifyGlobalPrefixCodeIsConfiguredForLibrary($xData));
    }

    /**
     * testGetExceptionClassStrings
     * @covers \pvc\err\XDataTestMaster::getThrowableClassStrings
     */
    public function testGetExceptionClassStrings(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\allGood\_pvcXData();
        /**
         * There are 10 files in the fixture dir, 6 of which are exceptions
         */
        self::assertEquals(6, count($this->xDataTestMaster->getThrowableClassStrings($xData)));
    }

    /**
     * testGetExceptionClassStringsReturnsEmptyArrayOnEmptyDirectory
     * @covers \pvc\err\XDataTestMaster::getThrowableClassStrings
     */
    public function testGetExceptionClassStringsReturnsEmptyArrayOnEmptyDirectory(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\noExceptionsDefined\_pvcXData();
        $throwableClassStrings = $this->xDataTestMaster->getThrowableClassStrings($xData);
        self::assertIsArray($throwableClassStrings);
        self::assertEquals(0, count($throwableClassStrings));
    }

    /**
     * dataProviderForVerifyKeysMatchClassStrings
     * @return array[]
     */
    public function dataProviderForVerifyKeysMatchClassStrings(): array
    {
        return [
            [
                '\pvcTests\err\fixturesForXDataTestMaster\moreCodesThanExceptions',
                '/codes*/',
                false
            ],

            [
                '\pvcTests\err\fixturesForXDataTestMaster\moreExceptionsThanCodes',
                '/exception*/',
                false
            ],

            [
                '\pvcTests\err\fixturesForXDataTestMaster\moreExceptionsThanMessages',
                '/exception*/',
                false
            ],

            [
                '\pvcTests\err\fixturesForXDataTestMaster\moreMessagesThanExceptions',
                '/messages*/',
                false
            ],

            [
                '\pvcTests\err\fixturesForXDataTestMaster\allGood',
                '',
                true
            ]
        ];
    }

    /**
     * testVerifyKeysMatchClassStringsFromDir
     * @dataProvider dataProviderForVerifyKeysMatchClassStrings
     * @covers       \pvc\err\XDataTestMaster::verifyXDataKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDir(string $dir, string $regex, bool $expectedResult): void
    {
        $xDataClassString = $dir . DIRECTORY_SEPARATOR . '_pvcXData';
        $xData = new $xDataClassString();
        $throwableClassStrings = $this->xDataTestMaster->getThrowableClassStrings($xData);

        if (!empty($regex)) {
            self::expectOutputRegex($regex);
        }

        self::assertEquals(
            $expectedResult,
            $this->xDataTestMaster->verifyXDataKeysMatchClassStringsFromDir(
                $xData,
                $throwableClassStrings
            )
        );
    }

    /**
     * testVerifyGetLocalCodesArrayHasUniqueIntegerValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalCodesArrayHasUniqueIntegerValues
     */
    public function testVerifyGetLocalCodesArrayHasIntegerValues(): void
    {
        $xData = new fixturesForXDataTestMaster\xDataFixtures\XDataCodesNotIntegers();
        self::expectOutputRegex('/not all exception codes are integers*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData));
    }

    /**
     * testVerifyGetLocalCodesArrayHasUniqueValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalCodesArrayHasUniqueIntegerValues
     */
    public function testVerifyGetLocalCodesArrayHasUniqueValues(): void
    {
        $xData = new fixturesForXDataTestMaster\xDataFixtures\XDataCodesNotUnique();
        self::expectOutputRegex('/not all exception codes are unique*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData));
    }

    /**
     * testVerifyGetMessagesArrayHasStringValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalMessagesArrayHasStringsForValues
     */
    public function testVerifyGetMessagesArrayHasStringValues(): void
    {
        $xData = new fixturesForXDataTestMaster\xDataFixtures\XDataMessagesNotStrings();
        self::expectOutputRegex('/not all exception messages are strings.*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalMessagesArrayHasStringsForValues($xData));
    }

    /**
     * dataProviderForTestExceptionHasConstructor
     * @return array[]
     */
    public function dataProviderForTestExceptionHasExplicitConstructor(): array
    {
        return [
            [ExceptionWithNoDefaultForPrev::class, true],
            [ExceptionWithImplicitConstructor::class, false],
            [ExceptionWithNoConstructor::class, false]
        ];
    }

    /**
     * testExceptionHasExplicitConstructor
     * @dataProvider dataProviderForTestExceptionHasExplicitConstructor
     * @covers       \pvc\err\XDataTestMaster::exceptionHasExplicitConstructor
     */
    public function testExceptionHasExplicitConstructor(string $classString, bool $expectedResult): void
    {
        $reflection = new ReflectionClass(($classString));
        self::assertEquals($expectedResult, $this->xDataTestMaster->exceptionHasExplicitConstructor($reflection));
    }

    /**
     * messageDataProvider
     * @return array<string, array<string>>
     */
    public function messageDataProvider(): array
    {
        return [
            ['This message has no parameters', []],
            ['There is a limit of ${limit} attempts before lockout.', ['limit']],
            ['This message also has ${no parameters} because of the whitespace', []],
            ['This ${message} as ${two} parameters', ['message', 'two']],
        ];
    }

    /**
     * testParseVariableNamesFromMessage
     * @param string $message
     * @param array $expectedParamArray
     * @dataProvider messageDataProvider
     * @covers       \pvc\err\XDataTestMaster::parseVariableNamesFromMessage
     */
    public function testParseVariableNamesFromMessage(string $message, array $expectedParamArray): void
    {
        self::assertEqualsCanonicalizing(
            $expectedParamArray,
            $this->xDataTestMaster->parseVariableNamesFromMessage($message)
        );
    }

    /**
     * dataProviderForParameterIsThrowable
     * @return array[]
     */
    public function dataProviderForParameterIsThrowable(): array
    {
        return [
            [SampleException::class, true],
            [SampleExceptionWithoutPrevParameter::class, false],
            [SampleExceptionWithBadPrevParameterType::class, false],
            [SampleExceptionWithUntypedPrevParameter::class, false],
            [SampleExceptionWithUnionTypedPrevParameter::class, false],
        ];
    }

    /**
     * testPrevParameterIsThrowable
     * @param string $classString
     * @param bool $expectedResult
     * @throws ReflectionException
     * @dataProvider dataProviderForParameterIsThrowable
     * @covers       \pvc\err\XDataTestMaster::parameterIsThrowable
     */
    public function testPrevParameterIsThrowable(string $classString, bool $expectedResult): void
    {
        $reflection = new ReflectionClass($classString);
        $params = $reflection->getConstructor()->getParameters();
        $lastParam = $params[count($params) - 1];
        self::assertEquals($expectedResult, $this->xDataTestMaster->parameterIsThrowable($lastParam));
    }

    /**
     * dataProviderForParameterHasDefaultValueOfNull
     * @return array[]
     */
    public function dataProviderForParameterHasDefaultValueOfNull(): array
    {
        return [
            [SampleExceptionWithNonOptionalPrevParameter::class, false],
            [SampleExceptionWithBadPrevParameterType::class, false],
            [SampleException::class, true],
        ];
    }

    /**
     * testPrevParameterHasDefaultValueOfNull
     * @param ReflectionParameter $param
     * @param bool $expectedResult
     * @throws ReflectionException
     * @dataProvider dataProviderForParameterHasDefaultValueOfNull
     * @covers       \pvc\err\XDataTestMaster::parameterHasDefaultValueOfNull
     */
    public function testPrevParameterHasDefaultValueOfNull(string $classString, bool $expectedResult): void
    {
        $reflection = new ReflectionClass($classString);
        $params = $reflection->getConstructor()->getParameters();
        $lastParam = $params[count($params) - 1];
        self::assertEquals($expectedResult, $this->xDataTestMaster->parameterHasDefaultValueOfNull($lastParam));
    }

    /**
     * dataProviderForCreateDummyParamValueBasedOnType
     * @return array
     */
    public function dataProviderForCreateDummyParamValueBasedOnType(): array
    {
        return [
            ['string', 'foo'],
            ['integer', 5],
            ['bool', true],
            ['other', '{other}'],
        ];
    }

    /**
     * testCreateDummyParamValueBasedOnType
     * @param string $dataType
     * @param $expectedValue
     * @dataProvider dataProviderForCreateDummyParamValueBasedOnType
     * @covers \pvc\err\XDataTestMaster::createDummyParamValueBasedOnType
     */
    public function testCreateDummyParamValueBasedOnType(string $dataType, $expectedValue): void
    {
        self::assertEquals($expectedValue, $this->xDataTestMaster->createDummyParamValueBasedOnType($dataType));
    }

    public function functionWithUntypedParameter($param)
    {
        return $param;
    }

    /**
     * testGetReflectionTypeNameReturnsStringWithUntypedParameter
     * @covers \pvc\err\XDataTestMaster::getReflectionTypeName
     */
    public function testGetReflectionTypeNameReturnsStringWithUntypedParameter(): void
    {
        $reflectedMethod = new \ReflectionMethod($this, 'functionWithUntypedParameter');
        $params = $reflectedMethod->getParameters();
        $param = $params[0];
        self::assertEquals('string', $this->xDataTestMaster->getReflectionTypeName(null));
    }

    public function functionWithNamedTypeParameter(int $someNumber)
    {
        return $someNumber;
    }

    /**
     * testGetReflectionTypeNameReturnsNameWithReflectionNameTypeParameter
     * @throws ReflectionException
     * @covers \pvc\err\XDataTestMaster::getReflectionTypeName
     */
    public function testGetReflectionTypeNameReturnsNameWithReflectionNameTypeParameter(): void
    {
        $reflectedMethod = new \ReflectionMethod($this, 'functionWithNamedTypeParameter');
        $params = $reflectedMethod->getParameters();
        $param = $params[0];
        self::assertEquals('int', $this->xDataTestMaster->getReflectionTypeName($param->getType()));
    }

    public function functionWithUnionTypeParameter(bool|string $param): bool|string
    {
        return $param;
    }

    /**
     * testGetReflectionTypeNameReturnsSingleNamedTypeForUnionTypeParameter
     * @throws ReflectionException
     * @covers \pvc\err\XDataTestMaster::getReflectionTypeName
     */
    public function testGetReflectionTypeNameReturnsSingleNamedTypeForUnionTypeParameter(): void
    {
        $reflectedMethod = new \ReflectionMethod($this, 'functionWithUnionTypeParameter');
        $params = $reflectedMethod->getParameters();
        /**
         * the order of the elements in the array is not deterministic, e.g. the types could be ['bool', 'string'] or
         * they could be ['string', bool'].
         */
        $param = $params[0];
        self::assertTrue(in_array(
            $this->xDataTestMaster->getReflectionTypeName($param->getType()),
            ['bool', 'string']
        ));
    }


    /**
     * dataProviderForVerifyExceptionConstructorIsCorrect
     * @return array[]
     */
    public function dataProviderForVerifyExceptionConstructorIsCorrect(): array
    {
        return [
            [
                ExceptionWithImplicitConstructor::class,
                null,
                true,
                'failed to assert that ExceptionWithImplicitConstructor is OK.'
            ],

            [
                ExceptionWithNoParameters::class,
                '/has no parameters/',
                false,
                'failed to assert that ExceptionWithNoParameters is not constructed correctly.'
            ],

            [
                ExceptionWithNoThrowableParameter::class,
                '/is not Throwable/',
                false,
                'failed to assert that ExceptionWithNoThrowableParameter is not constructed correctly.'
            ],

            [
                ExceptionWithNoDefaultForPrev::class,
                '/does not have a default value of null/',
                false,
                'failed to assert that ExceptionWithNoDefaultForPrev is not constructed correctly.'
            ],
        ];
    }

    /**
     * testVerifyExceptionConstructorIsCorrect
     * @throws ReflectionException
     * @dataProvider dataProviderForVerifyExceptionConstructorIsCorrect
     * @covers       \pvc\err\XDataTestMaster::verifyExceptionConstructorIsCorrect
     */
    public function testVerifyExceptionConstructorIsCorrect(
        string $exceptionClassString,
        ?string $outputRegex,
        bool $expectedResult,
        string $msg
    ): void {
        $xDataDir = '\pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures';
        $xDataClassString = $xDataDir . '\_XData';
        $xData = new $xDataClassString();

        $message = $xData->getXMessageTemplate($exceptionClassString);
        $messageParams = $this->xDataTestMaster->parseVariableNamesFromMessage($message);

        if ($outputRegex) {
            self::expectOutputRegex($outputRegex);
        }

        self::assertEquals(
            $expectedResult,
            $this->xDataTestMaster->verifyExceptionConstructorIsCorrect($exceptionClassString, $messageParams),
            $msg
        );
    }

    /**
     * dataProviderForTestVerifyExceptionAndMessageParametersMatch
     * @return array
     */
    public function dataProviderForTestVerifyExceptionAndMessageParametersMatch(): array
    {
        $msg1 = 'verifyExceptionAndMessageParameters failed to assert false when exception had no explicit ';
        $msg1 .= ' constructor and there were message variables.';

        $msg2 = 'verifyExceptionAndMessageParameters failed to assert true when exception had no explicit ';
        $msg2 .= 'constructor and there were message variables.';

        $msg3 = 'verifyExceptionAndMessageParameters failed to assert true when exception has explicit ';
        $msg3 .= 'constructor and constructor parameters match message variables.';

        $msg4 = 'verifyExceptionAndMessageParameters failed to assert false when exception has explicit ';
        $msg4 .= 'constructor and constructor parameters do not match message variables.';

        return [

            /**
             * no explicit constructor and has a message variable
             */
            [
                ExceptionWithImplicitConstructor::class,
                ['index'],
                false,
                $msg1
            ],

            /**
             * no constructor and has no message variable
             */
            [
                ExceptionWithNoConstructor::class,
                [],
                true,
                $msg2
            ],

            /**
             * has constructor with parameters and with message variables
             */
            [
                SampleException::class,
                ['bar', 'foo',],
                true,
                $msg3
            ],

            /**
             * has constructor with parameters and there are no message variables
             */
            [
                SampleException::class,
                [],
                false,
                $msg4
            ],
        ];
    }

    /**
     * testVerifyExceptionAndMessageParametersMatch
     * @param string $classString
     * @param array $messageVariables
     * @param bool $expectedResult
     * @param string $msg
     * @throws ReflectionException
     * @dataProvider dataProviderForTestVerifyExceptionAndMessageParametersMatch
     * @covers       \pvc\err\XDataTestMaster::verifyExceptionAndMessageParametersMatch()
     */
    public function testVerifyExceptionAndMessageParametersMatch(
        string $classString,
        array $messageVariables,
        bool $expectedResult,
        string $msg
    ): void {
        self::assertEquals(
            $expectedResult,
            $this->xDataTestMaster->verifyExceptionAndMessageParametersMatch($classString, $messageVariables),
            $msg
        );
    }

    /**
     * testVerifyExtendsPvcStockException
     * @throws ReflectionException
     * @covers \pvc\err\XDataTestMaster::verifyExceptionExtendsPvcStockException()
     */
    public function testVerifyExtendsPvcStockException(): void
    {
        $classString = ExceptionDoesNotExtendPvcStockException::class;
        self::assertFalse($this->xDataTestMaster->verifyExceptionExtendsPvcStockException($classString));

        $classString = ExceptionWithNoDefaultForPrev::class;
        self::asserttrue($this->xDataTestMaster->verifyExceptionExtendsPvcStockException($classString));
    }

    /**
     * testVerifyExceptionsCanBeInstantiated
     * @throws ReflectionException
     * @covers \pvc\err\XDataTestMaster::verifyExceptionCanBeInstantiated
     */
    public function testVerifyExceptionsCanBeInstantiated(): void
    {
        $classString = '\pvcTests\err\fixturesForXDataTestMaster\allGood\InvalidFilenameException';
        self::assertTrue($this->xDataTestMaster->verifyExceptionCanBeInstantiated($classString));
    }

    /**
     * testVerifyLibrary
     * @covers \pvc\err\XDataTestMaster::verifyLibrary
     */
    public function testVerifyLibrary(): void
    {
        /**
         * confirm that its own exception data is registered and consistent
         */
        $xData = new \pvc\err\err\_ErrXData();
        self::assertTrue($this->xDataTestMaster->verifyLibrary($xData));
    }
}
