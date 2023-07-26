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


    public function getXMessageVariables(string $messageTemplate): array
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
         * $matches[1] is an array of all the strings in the subject that match the first capturing subpattern.  In
         * this case, that is the variable name without the delimiters.  I.e. if it looks like ${variable} in the
         * message, then it appears as 'variable' in the array.
         */
        $regex = '/\$\{[^}]+}/';
        preg_match_all($regex, $messageTemplate, $matches);
        return $matches[0];
    }
}
