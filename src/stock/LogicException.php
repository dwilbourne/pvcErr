<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\stock;

/**
 * From the PHP manual:
 *
 * Exception that represents error in the program logic. This kind of exception should lead directly to a fix
 * in your code.
 *
 * @package pvcErr
 */
class LogicException extends Exception
{
}
