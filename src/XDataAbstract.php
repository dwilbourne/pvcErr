<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\interfaces\err\XDataInterface;

/**
 * Class ExceptionDataAbstract
 */
abstract class XDataAbstract implements XDataInterface
{
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
     * @function getLocalXCodes
     * @return array<class-string, int>
     */
    abstract public function getLocalXCodes(): array;

    /**
     * @function getXMessageTemplate
     * @param class-string $classString
     * @return string
     */
    public function getXMessageTemplate(string $classString): string
    {
        $messages = $this->getXMessageTemplates();
        return $messages[$classString] ?? '';
    }

    /**
     * @function getXMessageTemplates
     * @return array<class-string, string>
     */
    abstract public function getXMessageTemplates(): array;

    /**
     * @function countXMessageVariables
     * @param string $messageTemplate
     * @return int<0, max>
     */
    public function countXMessageVariables(string $messageTemplate): int
    {
        /**
         * starts with '${', character class includes any combo of characters except '}' (at least one), finishes
         * with a '}'.
         *
         * Thought about restricting the characters in the character class to those that can be in a legitimate
         * variable name, but PHP itself would kick those out with a
         * compile error when you created the exception with bad dummy variable names, so we can afford to slack here
         * a bit.....
         *
         * preg_match_all returns the number of matches it finds.
         */
        $regex = '/\$\{[^}]+\}/';
        /**
         * phpstan thinls preg_match_all can return false so use intval to coalesce it to 0.
         */
        return intval(preg_match_all($regex, $messageTemplate));
    }
}
