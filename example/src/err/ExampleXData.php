<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */
declare (strict_types=1);

namespace pvcExamples\err\src\err;

/**
 * Class ExceptionConstants
 */
class ExampleXData
{
    public const LOCALCODES = [
      MyException::class => 1000,
    ];

    public const  MESSAGES = [
      MyException::class => 'Exception message with param ${param}.'
    ];
}