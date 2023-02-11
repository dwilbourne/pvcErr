<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc;

use pvc\err\stock\RuntimeException;

/**
 * InvalidFilenameException should be thrown when someone tries to specify illegal characters in a filename.
 *
 * Class InvalidFilenameException
 *
 * @package pvcErr
 */
class InvalidFilenameException extends RuntimeException
{
}
