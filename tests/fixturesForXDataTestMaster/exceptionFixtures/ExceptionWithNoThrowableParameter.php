<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures;

use pvc\err\stock\LogicException;

class ExceptionWithNoThrowableParameter extends LogicException
{
    public function __construct(string $prev)
    {
        parent::__construct($prev);
    }
}
