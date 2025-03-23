<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc\php;

use pvc\err\stock\RuntimeException;
use Throwable;

/**
 * Class InvalidPHPVersionException
 */
class InvalidPHPVersionException extends RuntimeException
{
    public function __construct(string $currentVersion, string $minVersion, ?Throwable $previous = null)
    {
        parent::__construct($currentVersion, $minVersion, $previous);
    }
}
