<?php
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace tests\err\throwable;

use PHPUnit\Framework\TestCase;
use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\Msg;
use pvc\err\throwable\exception\stock_rebrands\Exception;
use pvc\err\throwable\Throwable;

class PvcErrTest extends TestCase
{

    protected string $msgText;
    protected Msg $msg;
    protected int $code;
    protected \Exception $previous;

    public function setUp(): void
    {
        $this->msgText = 'exception message';
        $vars = [];
        $this->msg = new Msg($vars, $this->msgText);
        $this->code = 0;

        $previousMsg = 'previous message';
        $previousCode = -1;
        $previousPrevious = null;
        $this->previous = new \Exception($previousMsg, $previousCode, $previousPrevious);
    }

    public function testExceptionNullMsg() : void
    {
        $e = new Exception();
        $msg = $e->getMsg();
        $expectedMsgtext = 'An unspecified exception has occurred.';
        self::assertEquals($expectedMsgtext, $msg->getMsgText());
    }


    /**
     * @function testExceptions
     * @param string $exceptionName
     * @param int $exceptionCode
     * @dataProvider pvcExceptionDataProvider
     */

    public function testPvcExceptions(string $exceptionName, int $exceptionCode) : void
    {
        $namespace = 'pvc\err\throwable\exception\pvc_exceptions\\';
        $qualifiedName = $namespace . $exceptionName;
        // these should all be configured such that if the code passed in is 0 then the
        // apprpriate constant is pulled from the constants file
        $e = new $qualifiedName($this->msg, 0, $this->previous);
        self::assertEquals($this->msgText, $e->getMsg()->getMsgText());
        self::assertEquals($exceptionCode, $e->getCode());
        self::assertEquals($this->previous, $e->getPrevious());
        self::assertTrue($e instanceof Throwable);
    }

    public function pvcExceptionDataProvider(): array
    {
        return [
            ['OutOfContextMethodCallException', ec::OUT_OF_CONTEXT_METHOD_CALL_EXCEPTION],
            ['InvalidArrayIndexException', ec::INVALID_ARRAY_INDEX_EXCEPTION],
            ['InvalidArrayValueException', ec::INVALID_ARRAY_VALUE_EXCEPTION],
            ['InvalidAttributeNameException', ec::INVALID_ATTRIBUTE_NAME_EXCEPTION],
            ['InvalidFilenameException', ec::INVALID_FILENAME_EXCEPTION],
            ['InvalidPHPVersionException', ec::INVALID_PHP_VERSION_EXCEPTION],
            ['InvalidTypeException', ec::INVALID_TYPE_EXCEPTION],
            ['InvalidValueException', ec::INVALID_VALUE_EXCEPTION],
            ['PregMatchFailureException', ec::PREG_MATCH_FAILURE_EXCEPTION],
            ['PregReplaceFailureException', ec::PREG_REPLACE_FAILURE_EXCEPTION],
            ['UnsetAttributeException', ec::UNSET_ATTRIBUTE_EXCEPTION],
        ];
    }

    /**
     * @function testStockRebrandExceptions
     * @param string $exceptionName
     * @param int $exceptionCode
     * @dataProvider stockRebrandExceptionDataProvider
     */
    public function testStockRebrandExceptions(string $exceptionName, int $exceptionCode) : void
    {
        $namespace = 'pvc\err\throwable\exception\stock_rebrands\\';
        $qualifiedName = $namespace . $exceptionName;
        $e = new $qualifiedName($this->msg, $this->code, $this->previous);
        self::assertEquals($this->msgText, $e->getMsg()->getMsgText());
        self::assertEquals($exceptionCode, $e->getCode());
        self::assertEquals($this->previous, $e->getPrevious());
        self::assertTrue($e instanceof Throwable);
    }

    public function stockRebrandExceptionDataProvider(): array
    {
        return [
            ['BadFunctionCallException', ec::BAD_FUNCTION_CALL_EXCEPTION],
            ['BadMethodCallException', ec::BAD_METHOD_CALL_EXCEPTION],
            ['ClosedGeneratorException', ec::CLOSED_GENERATOR_EXCEPTION],
            ['DomainException', ec::DOMAIN_EXCEPTION],
            ['DOMArgumentException', ec::DOM_ARGUMENT_EXCEPTION],
            ['DOMException', ec::DOM_EXCEPTION],
            ['DOMFunctionException', ec::DOM_FUNCTION_EXCEPTION],
            ['ErrorException', ec::ERROR_EXCEPTION],
            ['Exception', ec::EXCEPTION],
            ['IntlException', ec::INTL_EXCEPTION],
            ['InvalidArgumentException', ec::INVALID_ARGUMENT_EXCEPTION],
            ['InvalidDataTypeException', ec::INVALID_DATA_TYPE_EXCEPTION],
            ['LengthException', ec::LENGTH_EXCEPTION],
            ['LogicException', ec::LOGIC_EXCEPTION],
            ['OutOfBoundsException', ec::OUT_OF_BOUNDS_EXCEPTION],
            ['OutOfRangeException', ec::OUT_OF_RANGE_EXCEPTION],
            ['OverflowException', ec::OVERFLOW_EXCEPTION],
            ['RangeException', ec::RANGE_EXCEPTION],
            ['ReflectionException', ec::REFLECTION_EXCEPTION],
            ['RuntimeException', ec::RUNTIME_EXCEPTION],
            ['UnderflowException', ec::UNDERFLOW_EXCEPTION],
        ];
    }
}
