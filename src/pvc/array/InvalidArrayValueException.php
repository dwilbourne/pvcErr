<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc\array;

use pvc\err\stock\LogicException;
use Throwable;

/**
 * InvalidArrayValueException should be thrown when someone tries to access an array element using an invalid index
 */
class InvalidArrayValueException extends LogicException
{
    public function __construct(string $value, Throwable $prev = null)
    {
        parent::__construct($value, $prev);
    }
}
