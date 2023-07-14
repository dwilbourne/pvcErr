<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixtureForXDataTests;

use pvc\err\stock\LogicException;

/**
 * Class SampleExceptionWithNonOptionalPrevParameter
 */
class SampleExceptionWithNonOptionalPrevParameter extends LogicException
{
    public function __construct(int $limit, Throwable $prev)
    {
        parent::__construct($limit, $prev);
    }
}