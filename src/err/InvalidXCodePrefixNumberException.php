<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\err;

use pvc\err\stock\LogicException;
use Throwable;

/**
 * Class InvalidXCodePrefixNumberException
 */
class InvalidXCodePrefixNumberException extends LogicException
{
    public function __construct(int $badPrefix, ?Throwable $prev = null)
    {
        parent::__construct($badPrefix, $prev);
    }
}