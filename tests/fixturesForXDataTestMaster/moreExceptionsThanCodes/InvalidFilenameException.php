<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\moreExceptionsThanCodes;

use pvc\err\stock\LogicException;
use Throwable;

/**
 * InvalidFilenameException should be thrown when someone tries to specify illegal characters in a filename.
 */
class InvalidFilenameException extends LogicException
{
    public function __construct(string $badFileName, Throwable $previous = null)
    {
        parent::__construct($badFileName, $previous);
    }
}
