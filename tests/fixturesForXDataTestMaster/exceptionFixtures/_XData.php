<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures;

use pvc\err\XDataAbstract;
use pvc\interfaces\err\XDataInterface;

/**
 * Class _PvcExceptionLibrary
 * @package pvcErr
 */
class _XData extends XDataAbstract implements XDataInterface
{
    /**
     * @function getLocalXCodes
     * @return array<class-string, int>
     */
    public function getLocalXCodes(): array
    {
        return [
            ExceptionWithImplicitConstructor::class => 1001,
            ExceptionWithParametersNotMatchingMessage::class => 1002,
            ExceptionWithNoDefaultForPrev::class => 1003,
            ExceptionWithNoParameters::class => 1004,
            ExceptionWithNoThrowableParameter::class => 1005,
        ];
    }

    /**
     * @function getLocalXMessages
     * @return array<class-string, string>
     */
    public function getXMessageTemplates(): array
    {
        return [
            ExceptionWithImplicitConstructor::class => 'Message has no parameters.',
            ExceptionWithParametersNotMatchingMessage::class => 'Invalid array index ${index}.',
            ExceptionWithNoDefaultForPrev::class => 'Invalid array index ${index}.',
            ExceptionWithNoParameters::class => 'Invalid array index ${index}.',
            ExceptionWithNoThrowableParameter::class => 'Invalid array index ${index}.',
        ];
    }
}
