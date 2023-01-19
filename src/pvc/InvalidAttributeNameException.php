<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc;

use pvc\err\stock\LogicException;

/**
 * Class InvalidAttributeNameException
 *
 * InvalidAttributeNameException should be thrown when someone tries to set an object attribute to a
 * value with in incorrect type
 */
class InvalidAttributeNameException extends LogicException
{
}
