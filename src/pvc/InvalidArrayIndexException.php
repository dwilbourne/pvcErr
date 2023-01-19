<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc;

use pvc\err\stock\LogicException;

/**
 * InvalidArrayIndexException should be thrown when someone tries to access an array element using an invalid index
 *
 * Class InvalidArrayIndexException
 */
class InvalidArrayIndexException extends LogicException
{
}