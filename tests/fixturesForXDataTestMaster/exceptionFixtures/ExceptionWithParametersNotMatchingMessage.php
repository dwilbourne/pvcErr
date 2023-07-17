<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures;

use pvc\err\stock\LogicException;
use Throwable;

class ExceptionWithParametersNotMatchingMessage extends LogicException
{
    public function __construct(string $myIndex, Throwable $prev = null)
    {
        parent::__construct($myIndex, $prev);
    }
}
