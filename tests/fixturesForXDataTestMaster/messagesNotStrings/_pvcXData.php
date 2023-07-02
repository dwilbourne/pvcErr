<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\messagesNotStrings;

use pvc\err\XDataAbstract;
use pvc\interfaces\err\XDataInterface;
use pvcTests\err\fixturesForXDataTestMaster\codesNotIntegers\InvalidArrayIndexException;
use pvcTests\err\fixturesForXDataTestMaster\codesNotIntegers\InvalidArrayValueException;
use pvcTests\err\fixturesForXDataTestMaster\codesNotIntegers\InvalidFilenameException;

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
        ];
    }

    /**
     * @function getLocalXMessages
     * @return array<class-string, string>
     */
    public function getXMessageTemplates(): array
    {
        return [
            InvalidArrayIndexException::class => 9002,
            InvalidArrayValueException::class => 'Invalid array value ${value}.',
            InvalidFilenameException::class => 'filename %s is not valid.',
        ];
    }
}
