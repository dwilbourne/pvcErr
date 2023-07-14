<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\exceptionWithNoDefaultForPrev;

use pvc\err\stock\LogicException;
use Throwable;

class InvalidArrayIndexException extends LogicException
{
    public function __construct(Throwable $prev)
    {
        parent::__construct($prev);
    }
}
