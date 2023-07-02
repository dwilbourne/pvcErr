<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\noExceptionsDefined;

use pvc\err\XDataAbstract;
use pvc\interfaces\err\XDataInterface;
use pvcTests\err\fixturesForXDataTestMaster\allGood\InvalidArrayIndexException;
use pvcTests\err\fixturesForXDataTestMaster\allGood\InvalidArrayValueException;
use pvcTests\err\fixturesForXDataTestMaster\allGood\InvalidFilenameException;
use pvcTests\err\fixturesForXDataTestMaster\allGood\InvalidPHPVersionException;
use pvcTests\err\fixturesForXDataTestMaster\allGood\PregMatchFailureException;
use pvcTests\err\fixturesForXDataTestMaster\allGood\PregReplaceFailureException;

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
            PregMatchFailureException::class => 1005,
            PregReplaceFailureException::class => 1006,
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
            InvalidFilenameException::class => 'filename %s is not valid.',
            InvalidPHPVersionException::class => 'Invalid PHP version ${currentVersion} - must be at least ${minVersion}',
            PregMatchFailureException::class => 'preg_match failed: regex=${regex}; subject=${subject};',
            PregReplaceFailureException::class => 'preg_replace failed: regex=${regex}; subject=${subject}; replacement=${replacement}',
        ];
    }
}
