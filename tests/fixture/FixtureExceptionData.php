<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */
declare (strict_types=1);


namespace pvcTests\err\fixture;


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
          SampleDuplicateException::class => 1001,
          SampleException::class => 1002,
        ];
    }

    /**
     * getLocalXMessages
     * @return array<class-string, string>
     */
    public function getLocalXMessages(): array
    {
        return [
            SampleDuplicateException::class => 'some error message',
            SampleException::class => 'some error message with parameter %s',
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