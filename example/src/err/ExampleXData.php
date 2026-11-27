<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcExamples\err\src\err;

use pvc\err\XData;

/**
 * Class ExceptionConstants
 */
class ExampleXData extends XData
{
    public function getLocalXCodes(): array
    {
        return [
            MyException::class => 1000,
        ];
    }

    public function getXMessageTemplates(): array
    {
        return [
            MyException::class => 'Exception message with index ${index} and parameter ${param}.'
        ];
    }
}
