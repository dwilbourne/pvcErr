<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\exceptionWithNoParameters;

use pvc\err\stock\LogicException;

class InvalidArrayIndexException extends LogicException
{
    public function __construct()
    {
        parent::__construct();
    }
}
