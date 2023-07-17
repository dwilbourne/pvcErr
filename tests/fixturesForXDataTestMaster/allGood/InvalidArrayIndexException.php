<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\allGood;

use pvc\err\stock\LogicException;
use Throwable;

/**
 * ExceptionWithParametersNotMatchingMessage should be thrown when someone tries to access an array element using an
 * invalid index
 */
class InvalidArrayIndexException extends LogicException
{
    public function __construct(string $index, Throwable $prev = null)
    {
        parent::__construct($index, $prev);
    }
}
