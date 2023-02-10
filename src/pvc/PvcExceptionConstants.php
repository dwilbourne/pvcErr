<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc;

class PvcExceptionConstants
{
    public const LOCALCODES = [
        InvalidArrayIndexException::class => 1001,
        InvalidArrayValueException::class => 1002,
        InvalidAttributeNameException::class => 1003,
        InvalidFilenameException::class => 1004,
        InvalidPHPVersionException::class => 1005,
        PregMatchFailureException::class => 1006,
        PregReplaceFailureException::class => 1007,
    ];

    public const MESSAGES = [
        InvalidArrayIndexException::class => 'Invalid array index %s.',
        InvalidArrayValueException::class => 'Invalid array value %s.',
        InvalidAttributeNameException::class => 'Attribute %s does not exist or is not directly accessible.',
        InvalidFilenameException::class => 'filename %s is not valid.',
        InvalidPHPVersionException::class => 'Invalid PHP version - must be at least %s',
        PregMatchFailureException::class => 'preg_match failed: regex=%s; subject=%s;',
        PregReplaceFailureException::class => 'preg_replace failed: regex=%s; subject=%s; replacement=%s',
    ];
}
