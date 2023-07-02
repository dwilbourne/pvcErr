<?php

/**
 * @package pvcException
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixtureForXDataTests;

use pvc\err\stock\LogicException;
use Throwable;

/**
 * Class SampleExceptionWithMultipleParamTypes
 */
class SampleExceptionWithMultipleParamTypes extends LogicException
{
    public function __construct(string $param1, int $param2, bool $param3, object $param4, Throwable $previous = null)
    {
        parent::__construct($param1, $param2, $param3, $param4, $previous);
    }
}
