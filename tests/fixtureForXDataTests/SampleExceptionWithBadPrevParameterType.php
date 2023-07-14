<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixtureForXDataTests;

use Exception;
use pvc\err\stock\LogicException;
use ReflectionException;

/**
 * Class SampleExceptionWithBadPrevParameterType
 */
class SampleExceptionWithBadPrevParameterType extends LogicException
{
    /**
     * @param int $limit
     * @param string $prev
     * @throws ReflectionException
     * ok this is admittedly wacky, but the idea is to test that the last parameter has a default other than null
     */
    public function __construct(int $limit, string $prev = 'foo')
    {
        $prev = new Exception();
        parent::__construct($limit, $prev);
    }
}