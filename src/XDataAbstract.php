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
     * getLocalCodes
     * @return array<class-string, int>
     */
    abstract public function getLocalCodes(): array;

    /**
     * getLocalCode
     * @param class-string $classString
     * @return int
     */
    public function getLocalCode(string $classString): int
    {
        $codes = $this->getLocalCodes();
        return $codes[$classString] ?? 0;
    }

    /**
     * getLocalMessages
     * @return array<class-string, string>
     */
    abstract public function getLocalMessages(): array;

    /**
     * getLocalMessage
     * @param class-string $classString
     * @return string
     */
    public function getLocalMessage(string $classString): string
    {
        $messages = $this->getLocalMessages();
        return $messages[$classString] ?? '';
    }
}