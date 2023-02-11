<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvcExamples\err\src\client\err;

use pvc\err\XDataAbstract;

/**
 * Class _XData
 */
class _XData extends XDataAbstract
{
    /**
     * getLocalXCodes
     * @return int[]
     */
    public function getLocalXCodes(): array
    {
        return [
          MyException::class => 1001
        ];
    }

    /**
     * getLocalXMessages
     * @return string[]
     */
    public function getLocalXMessages(): array
    {
        return [
          MyException::class => 'some exception message with data = %s'
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
