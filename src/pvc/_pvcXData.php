<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvc\err\pvc;

use pvc\err\XDataAbstract;
use pvc\interfaces\err\XDataInterface;

/**
 * Class _PvcExceptionLibrary
 * @package pvcErr
 */
class _pvcXData extends XDataAbstract implements XDataInterface
{
    /**
     * @function getLocalXCodes
     * @return array<class-string, int>
     */
    public function getLocalXCodes(): array
    {
        return [
            InvalidArrayIndexException::class => 1001,
            InvalidArrayValueException::class => 1002,
            InvalidFilenameException::class => 1003,
            InvalidPHPVersionException::class => 1004,
        ];
    }

    /**
     * @function getLocalXMessages
     * @return array<class-string, string>
     */
    public function getXMessageTemplates(): array
    {
        return [
            InvalidArrayIndexException::class => 'Invalid array index ${index}.',
            InvalidArrayValueException::class => 'Invalid array value ${value}.',
            InvalidFilenameException::class => 'filename ${badFileName} is not valid.',
            InvalidPHPVersionException::class => 'Invalid PHP version ${currentVersion} - must be at least ${minVersion}',
        ];
    }
}
