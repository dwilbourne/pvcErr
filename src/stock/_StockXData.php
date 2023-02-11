<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvc\err\stock;

use pvc\err\XDataAbstract;
use pvc\interfaces\err\XDataInterface;

/**
 * Class _Pvc_ExceptionFactory
 * @package pvcErr
 */
class _StockXData extends XDataAbstract implements XDataInterface
{

    /**
     * @function getNamespace
     * @return string
     */
    public function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    /**
     * @function getDirectory
     * @return string
     */
    public function getDirectory(): string
    {
        return __DIR__;
    }
    /**
     * @function getLocalXCodes
     * @return array<class-string, int>
     */
    public function getLocalXCodes(): array
    {
        return [
            Exception::class => 1001,
            BadFunctionCallException::class => 1002,
            BadMethodCallException::class => 1003,
            ClosedGeneratorException::class => 1004,
            DomainException::class => 1005,
            DOMArgumentException::class => 1006,
            DOMException::class => 1007,
            DOMFunctionException::class => 1008,
            ErrorException::class => 1009,
            IntlException::class => 1010,
            InvalidArgumentException::class => 1011,
            InvalidDataTypeException::class => 1012,
            LengthException::class => 1013,
            LogicException::class => 1014,
            OutOfBoundsException::class => 1015,
            OutOfRangeException::class => 1016,
            OverflowException::class => 1017,
            RangeException::class => 1018,
            ReflectionException::class => 1019,
            RuntimeException::class => 1020,
            PharException::class => 1021,
            PDOException::class => 1022,
            UnderflowException::class => 1023,
            UnexpectedValueException::class => 1024,
            SodiumException::class => 1025,
        ];
    }

    /**
     * @function getLocalXMessages
     * @return array<class-string, string>
     */
    public function getLocalXMessages(): array
    {
        return [
            Exception::class => "An unspecified exception has occurred.",
            BadFunctionCallException::class => "Function is not defined or had missing / bad arguments.",
            BadMethodCallException::class => "Method is not defined in this object.",
            ClosedGeneratorException::class => "The generator was closed at the time it was referenced.",
            DomainException::class => "Value provided does not adhere to a defined valid data domain.",
            DOMArgumentException::class => "Invalid argument supplied to the DOM function / method.",
            DOMException::class => "A DOM exception has occurred.",
            DOMFunctionException::class => "Exception occurred invoking a DOM function or method.",
            ErrorException::class => "An ErrorException has occurred.",
            IntlException::class => "An IntlException has occurred.",
            InvalidArgumentException::class => "Invalid argument supplied.",
            InvalidDataTypeException::class => "Invalid data type supplied",
            LengthException::class => "A LengthException has occurred.",
            LogicException::class => "A LogicException has occurred.",
            OutOfBoundsException::class => "Index into array is out of bounds, no such index exists.",
            OutOfRangeException::class => "An OutOfRangeException has occurred.",
            OverflowException::class => "An OverflowException has occurred.",
            RangeException::class => "A RangeException has occurred.",
            ReflectionException::class => "Exception created trying to reflect an object.",
            PharException::class => "A PHAR Exception has occurred.",
            RuntimeException::class => "A RuntimeException has occurred.",
            PDOException::class => "A PDOException has occurred.",
            UnderflowException::class => "An UnderflowException has occurred.",
            UnexpectedValueException::class => "An UnexpectedValueException has occurred.",
            SodiumException::class => "A SodiumException has occurred.",

        ];
    }
}
