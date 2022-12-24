<?php

declare(strict_types=1);

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace tests\err;

use PHPUnit\Framework\TestCase;
use pvc\err\ErrConfig;

class ErrConfigTest extends TestCase
{
	/**
	 * testErrConfigReturnsCorrectGlobalCode
	 * @throws \Exception
	 * @covers \pvc\err\ErrConfig::createExceptionCode
	 * @covers \pvc\err\ErrConfig::getLibraryCode
	 */
	public function testErrConfigReturnsCorrectGlobalCode(): void
	{
		$libName = "pvcExceptions";
		$localCode = 1001;
		$expectedGlobalCode = 10001001;
		self::assertEquals($expectedGlobalCode, ErrConfig::createExceptionCode($libName, $localCode));
	}

	/**
	 * testErrConfigReturnsLocalCodeGivenBadLibraryName
	 * @throws \Exception
	 * @covers \pvc\err\ErrConfig::createExceptionCode
	 * @covers \pvc\err\ErrConfig::getLibraryCode
	 */
	public function testErrConfigReturnsLocalCodeGivenBadLibraryName(): void
	{
		$libName = "nonExistentLib";
		$localCode = 1000;
		self::assertEquals($localCode, ErrConfig::createExceptionCode($libName, $localCode));
	}
}
