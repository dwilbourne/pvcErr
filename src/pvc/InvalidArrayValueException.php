<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc;

use pvc\err\stock\LogicException;

/**
 * InvalidArrayValueException should be thrown when someone tries to access an array element using an invalid index
 *
 * Class InvalidArrayValueException
 * @package pvcErr
 */
class InvalidArrayValueException extends LogicException
{
}
