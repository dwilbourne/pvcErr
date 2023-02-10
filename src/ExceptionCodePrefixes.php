<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

/**
 * Class ErrConfig
 */
class ExceptionCodePrefixes
{
    public const PREFIXES = [
        "pvcExamples\err\err" => 1001,
        "pvc\err\stock" => 1002,
        "pvc\err\pvc" => 1003,
    ];

    public static function getXCodePrefix(string $namespace) : int
    {
        return self::PREFIXES[$namespace] ?? 0;
    }

    public static function getXCodePrefixes() : array
    {
        return self::PREFIXES;
    }

}
