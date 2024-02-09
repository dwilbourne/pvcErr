<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\err\err\InvalidXCodePrefixNumberException;
use pvc\interfaces\err\XCodePrefixesInterface;

/**
 * Class XCodePrefixes
 */
class XCodePrefixes implements XCodePrefixesInterface
{
    /**
     * @var array<string, int>
     * array of namespace => exception code prefix pairs, each prefix must be unique
     */
    protected static array $prefixes = [
        'pvcExamples\\err\\err' => 900,
        'pvcTests\\err\\fixtureForXDataTests' => 901,
        'pvc\\err\\err' => 902,
        'pvc\\err\\pvc' => 903,
        'pvc\\config\\err' => 904,
        'pvc\\msg\\err' => 905,
        'pvc\\intl\\err' => 906,
        'pvc\\filtervar\\err' => 907,
        'pvc\\regex\\err' => 908,
        'pvc\\validator\\err' => 909,
        'pvc\\http\\err' => 910,
        'pvc\\parser\\err' => 911,
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
     * @function getXCodePrefixes
     * @return array<string, int>
     */
    public static function getXCodePrefixes(): array
    {
        return self::$prefixes;
    }

    /**
     * addXCodePrefix
     * @param string $nameSpace
     * @param int $prefix
     * @throws InvalidXCodePrefixNumberException
     */
    public static function addXCodePrefix(string $nameSpace, int $prefix): void
    {
        /**
         * there is no need to check if $nameSpace is a valid namespace name.  The code inside the pvc exception
         * class supplies the namespace name it needs to getXCodePrefix.  The worst that can happen is it does not find
         * it in the prefixes array and getXCodePrefix returns 0.
         */
        if (in_array($prefix, self::$prefixes) || ($prefix < self::MIN_APPLICATION_PREFIX)) {
            throw new InvalidXCodePrefixNumberException($prefix);
        }
        self::$prefixes[$nameSpace] = $prefix;
    }
}
