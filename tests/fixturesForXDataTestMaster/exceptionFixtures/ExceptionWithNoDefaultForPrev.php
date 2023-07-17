<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures;

use pvc\err\stock\LogicException;
use Throwable;

class ExceptionWithNoDefaultForPrev extends LogicException
{
    public function __construct(Throwable $prev)
    {
        parent::__construct($prev);
    }
}
