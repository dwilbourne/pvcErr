<?php
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace tests\err\throwable;

use PHPUnit\Framework\TestCase;
use pvc\err\throwable\exception\pvc_exceptions\OutOfContextMethodCallMsg;
use pvc\err\throwable\exception\pvc_exceptions\InvalidArrayValueMsg;
use pvc\err\throwable\exception\pvc_exceptions\InvalidTypeMsg;
use pvc\err\throwable\exception\pvc_exceptions\InvalidValueMsg;
use pvc\err\throwable\exception\pvc_exceptions\InvalidArrayIndexMsg;
use pvc\err\throwable\exception\pvc_exceptions\InvalidAttributeNameMsg;
use pvc\err\throwable\exception\pvc_exceptions\InvalidFilenameMsg;
use pvc\err\throwable\exception\pvc_exceptions\InvalidPHPVersionMsg;
use pvc\err\throwable\exception\pvc_exceptions\PregMatchFailureMsg;
use pvc\err\throwable\exception\pvc_exceptions\PregReplaceFailureMsg;
use pvc\err\throwable\exception\pvc_exceptions\UnsetAttributeMsg;
use pvc\err\throwable\exception\stock_rebrands\BadFunctionCallMsg;
use pvc\err\throwable\exception\stock_rebrands\BadMethodCallMsg;
use pvc\err\throwable\exception\stock_rebrands\ClosedGeneratorMsg;
use pvc\err\throwable\exception\stock_rebrands\DOMArgumentMsg;
use pvc\err\throwable\exception\stock_rebrands\DOMFunctionMsg;
use pvc\err\throwable\exception\stock_rebrands\InvalidArgumentMsg;
use pvc\err\throwable\exception\stock_rebrands\InvalidDataTypeMsg;

/**
 * Class PvcErrMsgTest
 */
class PvcErrMsgTest extends TestCase
{

    public function testIncompleteObjectConfigurationMsg() : void
    {
        $objectName = 'foo';
        $methodName = 'bar';
        $additionalMsg = 'this is an additional message';
        $msg = new OutOfContextMethodCallMsg($objectName, $methodName, $additionalMsg);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidValueMsg() : void
    {
        $name = 'foo';
        $value = 'bar';
        $additionMessage = 'this is an additional message';
        $msg = new InvalidValueMsg($name, $value, $additionMessage);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidArgumentMsg() : void
    {
        $expectedDataType = 'foo';
        $msg = new InvalidArgumentMsg($expectedDataType);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidArrayIndexMsg() : void
    {
        $indexValue = 'foo';
        $additionalMsg = 'this is an additional message.';
        $msg = new InvalidArrayIndexMsg($indexValue, $additionalMsg);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidArrayValueMsg() : void
    {
        $arrayValue = 'foo';
        $msg = new InvalidArrayValueMsg($arrayValue);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidAttributeNameMsg() : void
    {
        $attributeName = 'foo';
        $msg = new InvalidAttributeNameMsg($attributeName);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidTypeMsg() : void
    {
        $name = 'foo';
        $types = ['int', 'string'];
        $msg = new InvalidTypeMsg($name, $types);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidFilenameMsg() : void
    {
        $filename = ',/.,.,';
        $msg = new InvalidFilenameMsg($filename);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidPhpVersionMsg() : void
    {
        $minPhpVersion = '7.0.0';
        $msg = new InvalidPhpVersionMsg($minPhpVersion);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testPregMatchFailureMsg() : void
    {
        $regex = '/abc/';
        $subject = 'xyz';
        $msg = new PregMatchFailureMsg($regex, $subject);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testPregReplaceFailureMsg() : void
    {
        $regex = '/abc/';
        $subject = 'xyz';
        $replace = '123';
        $msg = new PregReplaceFailureMsg($regex, $subject, $replace);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testUnsetAttributeMsg() : void
    {
        $attributeName = 'foo';
        $msg = new UnsetAttributeMsg($attributeName);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testBadFunctionCallMsg() : void
    {
        $callbackName = 'foo';
        $msg = new BadFunctionCallMsg($callbackName);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testBadMethodCallMsg() : void
    {
        $methodName = 'foo';
        $msg = new BadMethodCallMsg($methodName);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testClosedGeneratorMsg() : void
    {
        $generatorName = 'foo';
        $msg = new ClosedGeneratorMsg($generatorName);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testDOMArgumentMsg() : void
    {
        $argName = 'foo';
        $methodName = 'bar';
        $msg = new DOMArgumentMsg($argName, $methodName);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testDOMFunctionMsg() : void
    {
        $DOMFunctionName = 'bar';
        $msg = new DOMFunctionMsg($DOMFunctionName);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }

    public function testInvalidDataTypeMsg() : void
    {
        $dataType = 'bar';
        $msg = new InvalidDataTypeMsg($dataType);
        $msgOutput = $msg->format();
        self::assertTrue(is_string($msgOutput));
        self::assertTrue(0 < strlen($msgOutput));
    }
}
