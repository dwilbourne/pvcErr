<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\interfaces\err\ExceptionFactoryInterface;
use Throwable;

/**
 * Top level class used to create exceptions in the pvc libraries.
 *
 *
 * Class ExceptionFactoryAbstract
 */
abstract class ExceptionFactoryAbstract implements ExceptionFactoryInterface
{
    /**
     * @var ExceptionLibraryCodes.  Object which manages library codes and can return one for a given exception.
     */
    protected ExceptionLibraryCodes $libraryCodes;

    /**
     * @var array<class-string, int>.  Array of local codes for an exception library
     */
    protected array $localCodes;

    /**
     * @var array<class-string, string>.  Array of local messages for an exception library.
     */
    protected array $localMessages;

    /**
     * getLocalCodes
     * @return array<class-string, int>.
     */
	abstract protected function getLocalCodes() : array;

    /**
     * getLocalMessages
     * @return array<class-string, string>
     */
	abstract protected function getLocalMessages(): array;

    /**
     * @param ExceptionLibraryCodes $libraryCodes
     */
    public function __construct(ExceptionLibraryCodes $libraryCodes)
    {
        $this->libraryCodes = $libraryCodes;
        $this->localCodes = $this->getLocalCodes();
        $this->localMessages = $this->getLocalMessages();
    }

    /**
     * createExceptionCode
     * @param class-string $classString
     * @return int
     */
    protected function createExceptionCode(string $classString): int
    {
        $globalPrefix = (string) $this->libraryCodes->getLibraryCode($classString);
        $localCode = $this->localCodes[$classString];
        return (int)($globalPrefix . $localCode);
    }

    /**
     * createException
     * @param class-string $classString
     * @param array<mixed> $params
     * @param Throwable|null $prev
     * @return Throwable
     */
    public function createException(string $classString, array $params = [], Throwable $prev = null): Throwable
    {
        $code = $this->createExceptionCode($classString);

        /**
         * usually params can be automatically converted to strings, but if something weird happens like passing an
         * object in as a param, let's be certain vsprintf can deal with it.
         */
        $newParams = [];
        foreach ($params as $param) {
            $newParams[] = (is_scalar($param) ? $param : gettype($param));
        }

        $message = vsprintf($this->localMessages[$classString], $newParams);

        /** @var Throwable $exception */
        $exception = new $classString($message, $code, $prev);
        return $exception;
    }
}
