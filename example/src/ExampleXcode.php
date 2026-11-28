<?php

namespace pvcExamples\err\src;

use pvc\err\metadata\ErrorCategory;
use pvc\err\metadata\Packages;
use pvc\err\metadata\RetryPolicy;
use pvc\err\metadata\Severity;
use pvc\interfaces\err\XCodeInterface;

enum ExampleXcode: string implements XCodeInterface
{
    case InvalidData = 'PVC_ERR_001';

    public function code(): string
    {
        return $this->value;
    }

    public function messageTemplate(): string
    {
        return match ($this) {
            self::InvalidData => 'Invalid data: index = {index:%s}, parameter = {param:%d}',
        };
    }

    public function package(): int
    {
        return Packages::PVC_ERR->value;
    }

    public function severity(): int
    {
        return Severity::ERROR->value;
    }

    public function exceptionType(): int
    {
        return ErrorCategory::Validation->value;
    }

    public function retryPolicy(): int
    {
        return RetryPolicy::None->value;
    }
}
