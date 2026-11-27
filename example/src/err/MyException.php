<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcExamples\err\src\err;

use pvc\err\stock\LogicException;

/**
 * Class MyException
 */
class MyException extends LogicException
{
    public function __construct(string $index1, string $param2, $previous = null)
    {
        parent::__construct($index1, $param2, $previous);
    }
}
