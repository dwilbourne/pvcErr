<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvc\err\pvc\file;

use pvc\err\XDataAbstract;
use pvc\interfaces\err\XDataInterface;

/**
 * Class _PvcExceptionLibrary
 * @package pvcErr
 */
class _pvcFileXData extends XDataAbstract implements XDataInterface
{
    /**
     * @function getLocalXCodes
     * @return array<class-string, int>
     */
    public function getLocalXCodes(): array
    {
        return [
            InvalidFilenameException::class => 1003,
            FileNotReadableException::class => 1005,
            FileDoesNotExistException::class => 1006,
        ];
    }

    /**
     * @function getLocalXMessages
     * @return array<class-string, string>
     */
    public function getXMessageTemplates(): array
    {
        return [
            InvalidFilenameException::class => 'filename ${badFileName} is not valid.',
            FileNotReadableException::class => '${filePath} is not readable',
            FileDoesNotExistException::class => '${filePath} is not a valid file or directory name',
        ];
    }
}
