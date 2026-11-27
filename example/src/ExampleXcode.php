<?php

namespace pvcExamples\err\src;

use pvc\err\metadata\ExceptionType;
use pvc\err\metadata\Packages;
use pvc\err\metadata\Severity;
use pvc\interfaces\err\XCodeInterface;

enum ExampleXcode: string implements XCodeInterface
{
    case InvalidData = 'PVC_ERR_001';
    case SomethingBad = 'PVC_ERR_002';


    public function code(): string
    {
        return $this->value;
    }

    public function messageTemplate(): string
    {
        return match ($this) {
            self::InvalidData => 'Invalid data: index = {index:%s}, parameter = {param:%d}',
            self::SomethingBad => 'Something bad happened: {param4:%s}',
        };
    }

    public function package(): string
    {
        return Packages::PVC_EXAMPLES->value;
    }

    public function severity(): string
    {
        return Severity::ERROR->value;
    }

    public function exceptionType(): string
    {
        return ExceptionType::NotFound->value;
    }
}
