<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use Throwable;

trait ExceptionFactoryTrait
{
	/**
	 * createException
	 * @param string $classString
	 * @param array<mixed> $params
	 * @param Throwable|null $prev
	 * @return Object
	 * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
	 */
	public static function createException(string $classString, array $params = [], Throwable $prev = null): object
	{
		$localCode = self::CODES[$classString] ?? 0;
		/** @noinspection PhpUnhandledExceptionInspection */
		$globalCode = ErrConfig::createExceptionCode(self::LIBRARY_NAME, $localCode);
		$message = vsprintf(self::MESSAGES[$classString] ?? "", $params);
		return new $classString($message, $globalCode, $prev);
	}
}
