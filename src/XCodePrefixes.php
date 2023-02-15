<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\interfaces\err\XCodePrefixesInterface;

/**
 * Class ErrConfig
 */
class XCodePrefixes implements XCodePrefixesInterface
{
    /**
     * array of namespace => exception code prefix pairs
     */
    protected const PREFIXES = [
        "pvcExamples\\err\\err" => 900,
        "pvcTests\\err\\fixtureForXDataTests" => 901,
        "pvc\\err\\pvc" => 902,

    ];

    /**
     * make this public so external applications can rely on it and to facilitate testing
     */
    public const MIN_APPLICATION_PREFIX = 1000;

    /**
     * @function getXCodePrefix
     * @param string $namespace
     * @return int
     */
    public static function getXCodePrefix(string $namespace): int
    {
        $prefixes = self::getXCodePrefixes();
        return $prefixes[$namespace] ?? 0;
    }

    /**
     * @function getPvcXCodePrefixes
     * @return int[]
     */
    public static function getPvcXCodePrefixes(): array
    {
        return self::PREFIXES;
    }

    /**
     * returns empty array if environment variable is not set, points to a non-existent / non-readable file,
     * unparseable file, php file which does not return an array, or if the array is badly shaped / typed.
     *
     * @function getExternalXCodePrefixes
     * @return array<string, int>
     */
    public static function getExternalXCodePrefixes(): array
    {
        $prefixes = [];

        $filePath = getenv('XCodePrefixes');

        if ($filePath !== false && is_readable($filePath)) {
            /**
             * include returns false if the file is not parseable but unlike require, it does NOT halt script execution
             */
            $applicationPrefixes = include $filePath;
            if (is_array($applicationPrefixes)) {
                foreach ($applicationPrefixes as $namespace => $prefix) {
                    if (is_string($namespace) && is_int($prefix) && ($prefix >= self::MIN_APPLICATION_PREFIX)) {
                        $prefixes[$namespace] = $prefix;
                    }
                }
            }
        }
        return $prefixes;
    }

    /**
     * combines pvc's exception prefixes with external application's prefixes.
     * @function getXCodePrefixes
     * @return array<string, int>
     */
    public static function getXCodePrefixes(): array
    {
        return array_merge(self::getPvcXCodePrefixes(), self::getExternalXCodePrefixes());
    }
}
