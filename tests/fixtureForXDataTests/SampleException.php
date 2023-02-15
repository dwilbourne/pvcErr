<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixtureForXDataTests;

use pvc\err\stock\LogicException;
use Throwable;

/**
 * Class SampleException
 */
class SampleException extends LogicException
{
    public function __construct(string $bar, string $foo, Throwable $previous = null)
    {
        parent::__construct($bar, $foo, $previous);
    }
}
