<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixtureForXDataTests;

use pvc\err\stock\LogicException;

/**
 * Class SampleExceptionWithoutPrevParameter
 */
class SampleExceptionWithoutPrevParameter extends LogicException
{
    public function __construct(string $limit)
    {
        parent::__construct($limit);
    }
}