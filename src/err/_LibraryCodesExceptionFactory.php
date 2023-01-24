<?php

/**
 * @packager pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvc\err\err;

use pvc\err\ExceptionFactoryAbstract;

/**
 * Class _LibraryCodesExceptionFactory
 */
class _LibraryCodesExceptionFactory extends ExceptionFactoryAbstract
{
    /**
     * getLocalCodes
     * @return array<class-string, int>
     */
    protected function getLocalCodes() : array
    {
        return [
            LibraryCodeFileGetContentsException::class => 1001,
            LibraryCodeFileNotParseableJsonException::class => 1002,
            LibraryCodeFileDoesNotParseToAnArrayException::class => 1003,
            LibraryCodeArrayElementIsInvalidException::class => 1004,
            LibraryCodeValueAlreadyInUseException::class => 1006,
            InvalidExceptionClassStringException::class => 1007,
            LibraryCodeFileNotWriteableException::class => 1008,
        ];
    }

    /**
     * @function getLocalMessages
     * @return array<class-string, string>
     */
    protected function getLocalMessages() : array
    {
        return [
            LibraryCodeFileGetContentsException::class => 'Library code file argument %s is not readable or does not exist.',
            LibraryCodeFileNotParseableJsonException::class => 'Library code file argument %s is not a parseable json file',
            LibraryCodeFileDoesNotParseToAnArrayException::class => 'Library code file %s does not parse to an array.',
            LibraryCodeArrayElementIsInvalidException::class => 'Key value pair [%s, %s] invalid: must be <string, int>.',
            LibraryCodeValueAlreadyInUseException::class => 'Library code %s is already in use by namespace %s.',
            InvalidExceptionClassStringException::class => "Reflection of class string of exception failed (%s).  Either the class is not defined or the class does not implement \Throwable",
            LibraryCodeFileNotWriteableException::class => "LIbrary code file %s is not writeable.",
        ];
    }
}
