<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcExamples\err\src;

use pvcExamples\err\src\err\MyException;

/**
 * Class ExampleClass
 */
class ExampleClass
{
    public function ThrowAnException(): void
    {
        /**
         * I prefer to type all the arguments as strings before pushing them into the exception constructor
         */
        $index = (string)32;
        $param = 'a string';
        $previous = null;
        throw new MyException($index, $param, $previous);
    }
}
