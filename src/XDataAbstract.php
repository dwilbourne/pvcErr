<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */
declare (strict_types=1);

namespace pvc\err;

use pvc\interfaces\err\XDataInterface;

/**
 * Class ExceptionDataAbstract
 */
abstract class XDataAbstract implements XDataInterface
{

    /**
     * @function getLocalXCodes
     * @return array<class-string, int>
     */
    abstract public function getLocalXCodes(): array;

    /**
     * @function getLocalXCode
     * @param class-string $classString
     * @return int
     */
    public function getLocalXCode(string $classString): int
    {
        $codes = $this->getLocalXCodes();
        return $codes[$classString] ?? 0;
    }

    /**
     * @function getLocalXMessages
     * @return array<class-string, string>
     */
    abstract public function getLocalXMessages(): array;

    /**
     * @function getLocalXMessage
     * @param class-string $classString
     * @return string
     */
    public function getLocalXMessage(string $classString): string
    {
        $messages = $this->getLocalXMessages();
        return $messages[$classString] ?? '';
    }
}