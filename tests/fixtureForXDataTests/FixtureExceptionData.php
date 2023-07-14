<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvcTests\err\fixtureForXDataTests;

use pvc\err\XDataAbstract;
use pvc\interfaces\err\XDataInterface;

/**
 * Class FixtureExceptionData
 */
class FixtureExceptionData extends XDataAbstract implements XDataInterface
{
    /**
     * getLocalXCodes
     * @return array<class-string, int>
     */
    public function getLocalXCodes(): array
    {
        return [
            SampleException::class => 1001,
            SampleExceptionWithMultipleParamTypes::class => 1002,
        ];
    }

    /**
     * getLocalXMessages
     * @return array<class-string, string>
     */
    public function getXMessageTemplates(): array
    {
        return [
            SampleException::class => 'some error message with parameter ${foo} and parameter ${bar}',
            SampleExceptionWithMultipleParamTypes::class => 'string parameter = ${param1}, int parameter = ${param2}, bool parameter = ${param3}, object parameter = ${param4}.',
        ];
    }
}
