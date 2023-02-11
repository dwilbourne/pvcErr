<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvc\err\err;

use pvc\err\XDataAbstract;

/**
 * Class _XData
 *
 * Notice that the exception codes and messages for the XCodePrefixes class are not here.  XFactory depends on
 * XCodePrefixes in order to throw an exception so in order to avoid circular dependencies, XCodePrefixes keeps its
 * own codes and messages internally.
 */
class _XData extends XDataAbstract
{
    /**
     * @function getLocalXCodes
     * @return int[]
     */
    public function getLocalXCodes(): array
    {
        return [
            XFactoryClassStringArgumentException::class => 1004,
            XFactoryMissingXDataException::class => 1005,
        ];
    }

    /**
     * @function getLocalXMessages
     * @return string[]
     */
    public function getLocalXMessages(): array
    {
        return [
            XFactoryClassStringArgumentException::class => "Reflection of exception class string (%s) failed.  Either the class is not defined or the class does not implement \Throwable.",
            XFactoryMissingXDataException::class => "No exception library found in namespace %s.",
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
