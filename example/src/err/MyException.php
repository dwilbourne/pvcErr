<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare (strict_types=1);

namespace pvcExamples\err\src\err;

use pvc\err\stock\LogicException;

/**
 * Class MyException
 */
class MyException extends LogicException
{
    public function __construct(string $param, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}