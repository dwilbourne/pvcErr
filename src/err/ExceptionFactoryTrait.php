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
	public static function createException(string $classString, array $params = [], Throwable $prev = null): \Exception
	{
		$localCode = self::CODES[$classString] ?? 0;
		/** @noinspection PhpUnhandledExceptionInspection */
		$globalCode = ErrConfig::createExceptionCode(self::LIBRARY_NAME, $localCode);
		// usually params can be automatically converted to strings, but if something weird happens like passing an
		// object in as a param, let's be certain vsprintf can deal with it.
		foreach($params as $param) {
			$newParams[] = (is_scalar($param) ? $param : gettype($param));
		}
		$message = vsprintf(self::MESSAGES[$classString] ?? "", $newParams);
		return new $classString($message, $globalCode, $prev);
	}
}
