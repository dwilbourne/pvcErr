<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use Exception;
use pvc\interfaces\err\ErrConfigInterface;

/**
 * Class ErrConfig
 */
class ErrConfig implements ErrConfigInterface
{
    public const LIBRARY_CODES = [
        "pvcExceptions" => 1001,
        "pvcBrandedStockExceptions" => 1002,
        "pvcList" => 1003,
        "pvcTree" => 1004,
	    "pvcFileTree" => 1005,
    ];

    /**
     * createExceptionCode
     * @param string $libraryName
     * @param int $localExceptionCode
     * @return int
     * @throws Exception
     */
    public static function createExceptionCode(string $libraryName, int $localExceptionCode): int
    {
        $globalPrefix = (string)self::getLibraryCode($libraryName);
        return (int)($globalPrefix . $localExceptionCode);
    }

    /**
     * getLibraryCode
     * @param string $libraryName
     * @return int|null
     * @throws Exception
     */
    public static function getLibraryCode(string $libraryName): ?int
    {
        return self::LIBRARY_CODES[$libraryName] ?? null;
    }
}
