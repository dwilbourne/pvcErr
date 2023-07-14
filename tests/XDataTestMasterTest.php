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
use pvcTests\err\fixturesForXDataTestMaster\allGood\_pvcXData;
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
     * testGetExceptionClassStrings
     * @covers \pvc\err\XDataTestMaster::getExceptionClassStrings
     */
    public function testGetExceptionClassStrings(): void
    {
        $xData = new _pvcXData();
        /**
         * There are 10 files in the fixture dir, 6 of which are exceptions
         */
        self::assertEquals(6, count($this->xDataTestMaster->getExceptionClassStrings($xData)));
    }

    /**
     * testGetExceptionClassStringsReturnsEmptyArrayOnEmptyDirectory
     * @covers \pvc\err\XDataTestMaster::getExceptionClassStrings
     */
    public function testGetExceptionClassStringsReturnsEmptyArrayOnEmptyDirectory(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\noExceptionsDefined\_pvcXData();
        $exceptionClassStrings = $this->xDataTestMaster->getExceptionClassStrings($xData);
        self::assertIsArray($exceptionClassStrings);
        self::assertEquals(0, count($exceptionClassStrings));
    }

    /**
     * testVerifyKeysMatchClassStringsFromDirFailsWithMoreCodesThanExceptions
     * @covers \pvc\err\XDataTestMaster::verifyKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDirFailsWithMoreCodesThanExceptions(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\moreCodesThanExceptions\_pvcXData();
        self::expectOutputRegex('/codes*/');
        self::assertFalse($this->xDataTestMaster->verifyKeysMatchClassStringsFromDir($xData));
    }

    /**
     * testVerifyKeysMatchClassStringsFromDirFailsWithMoreExceptionsThanCodes
     * @covers \pvc\err\XDataTestMaster::verifyKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDirFailsWithMoreExceptionsThanCodes(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\moreExceptionsThanCodes\_pvcXData();
        self::expectOutputRegex('/exception*/');
        self::assertFalse($this->xDataTestMaster->verifyKeysMatchClassStringsFromDir($xData));
    }

    /**
     * testVerifyKeysMatchClassStringsFromDirFailsWithMoreExceptionsThanMessages
     * @covers \pvc\err\XDataTestMaster::verifyKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDirFailsWithMoreExceptionsThanMessages(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\moreExceptionsThanMessages\_pvcXData();
        self::expectOutputRegex('/exception*/');
        self::assertFalse($this->xDataTestMaster->verifyKeysMatchClassStringsFromDir($xData));
    }

    /**
     * testVerifyKeysMatchClassStringsFromDirFailsWithMoreMessagesThanExceptions
     * @covers \pvc\err\XDataTestMaster::verifyKeysMatchClassStringsFromDir
     */
    public function testVerifyKeysMatchClassStringsFromDirFailsWithMoreMessagesThanExceptions(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\moreMessagesThanExceptions\_pvcXData();
        self::expectOutputRegex('/messages*/');
        self::assertFalse($this->xDataTestMaster->verifyKeysMatchClassStringsFromDir($xData));
    }

    /**
     * testVerifyGetLocalCodesArrayHasUniqueIntegerValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalCodesArrayHasUniqueIntegerValues
     */
    public function testVerifyGetLocalCodesArrayHasIntegerValues(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\codesNotIntegers\_pvcXData();
        self::expectOutputRegex('/not all exception codes are integers*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData));
    }

    /**
     * testVerifyGetLocalCodesArrayHasUniqueValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalCodesArrayHasUniqueIntegerValues
     */
    public function testVerifyGetLocalCodesArrayHasUniqueValues(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\codesNotUnique\_pvcXData();
        self::expectOutputRegex('/not all exception codes are unique*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData));
    }

    /**
     * testVerifyGetMessagesArrayHasStringValues
     * @covers \pvc\err\XDataTestMaster::verifyGetLocalMessagesArrayHasStringsForValues
     */
    public function testVerifyGetMessagesArrayHasStringValues(): void
    {
        $xData = new \pvcTests\err\fixturesForXDataTestMaster\messagesNotStrings\_pvcXData();
        self::expectOutputRegex('/not all exception messages are strings.*/');
        self::assertFalse($this->xDataTestMaster->verifyGetLocalMessagesArrayHasStringsForValues($xData));
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
     * testParameterIsThrowable
     * @param string $classString
     * @param bool $expectedResult
     * @throws ReflectionException
     * @dataProvider dataProviderForParameterIsThrowable
     * @covers       \pvc\err\XDataTestMaster::parameterIsThrowable
     */
    public function testParameterIsThrowable(string $classString, bool $expectedResult): void
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
     * testParameterHasDefaultValueOfNull
     * @param ReflectionParameter $param
     * @param bool $expectedResult
     * @throws ReflectionException
     * @dataProvider dataProviderForParameterHasDefaultValueOfNull
     * @covers       \pvc\err\XDataTestMaster::parameterHasDefaultValueOfNull
     */
    public function testParameterHasDefaultValueOfNull(string $classString, bool $expectedResult): void
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
     * testGetReflectionTypeNamePicksFirstTypeInTypeArray
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
     * dataProviderForVerifyExceptionAndMessageParameters
     * @return array[]
     */
    public function dataProviderForVerifyExceptionAndMessageParameters(): array
    {
        return [
            [
                '\pvcTests\err\fixturesForXDataTestMaster\allGood\_pvcXData',
                null,
                true
            ],
            [
                '\pvcTests\err\fixturesForXDataTestMaster\exceptionWithNoParameters\_pvcXData',
                '/has no parameters/',
                false
            ],
            [
                '\pvcTests\err\fixturesForXDataTestMaster\exceptionWithNoThrowableParameter\_pvcXData',
                '/is not Throwable/',
                false
            ],
            [
                '\pvcTests\err\fixturesForXDataTestMaster\exceptionWithNoDefaultForPrev\_pvcXData',
                '/does not have a default value of null/',
                false
            ],
            [
                '\pvcTests\err\fixturesForXDataTestMaster\exceptionParamsNotMatchMsgParams\_pvcXData',
                '/do not match the variable names/',
                false
            ],
        ];
    }

    /**
     * testVerifyExceptionParametersAndMessageParameters
     * @throws ReflectionException
     * @dataProvider dataProviderForVerifyExceptionAndMessageParameters
     * @covers       \pvc\err\XDataTestMaster::verifyExceptionParametersAndMessageParametersMatch
     */
    public function testVerifyExceptionAndMessageParameters(
        string $xDataClassString,
        ?string $outputRegex,
        bool $expectedResult
    ): void {
        $xData = new $xDataClassString();

        if ($outputRegex) {
            self::expectOutputRegex($outputRegex);
        }

        self::assertEquals(
            $expectedResult,
            $this->xDataTestMaster->verifyExceptionParametersAndMessageParametersMatch($xData)
        );
    }

    /**
     * testVerifyExceptionsCanBeInstantiated
     * @throws ReflectionException
     * @covers \pvc\err\XDataTestMaster::verifyExceptionsCanBeInstantiated
     */
    public function testVerifyExceptionsCanBeInstantiated(): void
    {
        $xData = new _pvcXData();
        self::assertTrue($this->xDataTestMaster->verifyExceptionsCanBeInstantiated($xData));
    }

    /**
     * testVerifyLibrary
     * @covers \pvc\err\XDataTestMaster::verifyLibrary
     */
    public function testVerifyLibrary(): void
    {
        $xData = new _pvcXData();
        self::assertTrue($this->xDataTestMaster->verifyLibrary($xData));
    }
}
