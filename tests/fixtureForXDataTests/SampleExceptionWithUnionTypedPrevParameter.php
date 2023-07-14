<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixtureForXDataTests;

use Exception;
use pvc\err\stock\LogicException;
use ReflectionException;
use Throwable;

/**
 * Class SampleExceptionWithBadPrevParameterType
 */
class SampleExceptionWithUnionTypedPrevParameter extends LogicException
{
    /**
     * @param int $limit
     * @param string $prev
     * @throws ReflectionException
     */
    public function __construct(int $limit, Throwable|string $prev)
    {
        $prev = new Exception();
        parent::__construct($limit, $prev);
    }
}