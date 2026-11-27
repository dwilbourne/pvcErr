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
        $index = 'a string';
        $param = 42;
        $previous = null;
        throw new ExampleException(
            ExampleXcode::InvalidData,
            ['index' => $index, 'param' => $param],
            $previous
        );
    }
}
