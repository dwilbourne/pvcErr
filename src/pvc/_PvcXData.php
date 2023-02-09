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
class _PvcXData extends XDataAbstract implements XDataInterface
{
    /**
     * @function getLocalXCodes
     * @return array<class-string, int>
     */
	public function getLocalXCodes() : array
    {
        return [
            InvalidArrayIndexException::class => 1001,
            InvalidArrayValueException::class => 1002,
            InvalidAttributeNameException::class => 1003,
            InvalidFilenameException::class => 1004,
            InvalidPHPVersionException::class => 1005,
            PregMatchFailureException::class => 1006,
            PregReplaceFailureException::class => 1007,
        ];
    }

    /**
     * @function getLocalXMessages
     * @return array<class-string, string>
     */
	public function getLocalXMessages() : array
    {
        return [
            InvalidArrayIndexException::class => 'Invalid array index %s.',
            InvalidArrayValueException::class => 'Invalid array value %s.',
            InvalidAttributeNameException::class => 'Attribute %s does not exist or is not directly accessible.',
            InvalidFilenameException::class => 'filename %s is not valid.',
            InvalidPHPVersionException::class => 'Invalid PHP version - must be at least %s',
            PregMatchFailureException::class => 'preg_match failed: regex=%s; subject=%s;',
            PregReplaceFailureException::class => 'preg_replace failed: regex=%s; subject=%s; replacement=%s',
        ];
    }

    /**
     * getNamespace
     * @return string
     */
    public function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    /**
     * getDirectory
     * @return string
     */
    public function getDirectory(): string
    {
        return __DIR__;
    }
}
