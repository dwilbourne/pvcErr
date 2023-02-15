<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc;

use pvc\err\stock\LogicException;
use Throwable;

/**
 * InvalidAttributeException should be thrown when someone tries to access an invalid attribute within an object
 */
class PregReplaceFailureException extends LogicException
{
    public function __construct(string $regex, string $subject, string $replacement, Throwable $previous = null)
    {
        parent::__construct($regex, $subject, $replacement, $previous);
    }
}
