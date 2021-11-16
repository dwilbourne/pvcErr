<?php
declare(strict_types=1);

namespace pvc\err\stock;

/**
 * constants for error and exception codes.
 */
class ExceptionConstants
{

    /**
     * codes for exceptions that are simply rebranding the stock exceptions that come with php
     */

    public const GENERIC_EXCEPTION_CODE = 100;
    public const BAD_FUNCTION_CALL_EXCEPTION = 106;
    public const BAD_METHOD_CALL_EXCEPTION = 107;
    public const CLOSED_GENERATOR_EXCEPTION = 101;
    public const DOMAIN_EXCEPTION = 108;
    public const DOM_ARGUMENT_EXCEPTION = 1023;
    public const DOM_EXCEPTION = 1023;
    public const DOM_FUNCTION_EXCEPTION = 1023;
    public const ERROR_EXCEPTION = 103;
    public const EXCEPTION = 100;
    public const INTL_EXCEPTION = 104;
    public const INVALID_ARGUMENT_EXCEPTION = 109;
    public const INVALID_DATA_TYPE_EXCEPTION = 109;
    public const LENGTH_EXCEPTION = 110;
    public const LOGIC_EXCEPTION = 105;
    public const OUT_OF_BOUNDS_EXCEPTION = 115;
    public const OUT_OF_RANGE_EXCEPTION = 111;
    public const OVERFLOW_EXCEPTION = 116;
    public const RANGE_EXCEPTION = 118;
    public const REFLECTION_EXCEPTION = 113;
    public const RUNTIME_EXCEPTION = 114;

    public const PHAR_EXCEPTION = 112;
    public const PDO_EXCEPTION = 117;
    public const UNDERFLOW_EXCEPTION = 119;
    public const UNEXPECTED_VALUE_EXCEPTION = 120;
    public const SODIUM_EXCEPTION = 121;
}
