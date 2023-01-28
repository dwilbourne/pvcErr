<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\err\err\ExceptionFactoryArgumentException;
use pvc\err\err\ExceptionFactoryMissingLibraryException;
use pvc\interfaces\err\ExceptionFactoryInterface;
use pvc\interfaces\err\ExceptionLibraryCodePrefixesInterface;
use pvc\interfaces\err\ExceptionLibraryDataInterface;
use ReflectionClass;
use Throwable;

/**
 * Top level class used to create exceptions in the pvc libraries.
 *
 *
 * Class ExceptionFactory
 */
class ExceptionFactory implements ExceptionFactoryInterface
{
    /**
     * @var ExceptionLibraryCodePrefixesInterface.  Object which manages library codes and can return one for a given
     * namespace.
     */
    protected ExceptionLibraryCodePrefixesInterface $libraryCodePrefixes;

    /**
     * @var array<ExceptionLibraryDataInterface>
     */
    protected array $exceptionLibraries = [];

    /**
     * @param ExceptionLibraryCodePrefixesInterface $libraryCodePrefixes
     */
    public function __construct(ExceptionLibraryCodePrefixesInterface $libraryCodePrefixes)
    {
        $this->libraryCodePrefixes = $libraryCodePrefixes;
    }

    /**
     * registerExceptionLibraryData
     * @param ExceptionLibraryDataInterface $libraryData
     */
    public function registerExceptionLibraryData(ExceptionLibraryDataInterface $libraryData): void
    {
        $this->exceptionLibraries[$libraryData->getNamespace()] = $libraryData;
    }

    /**
     * getExceptionLibraryData
     * @return ExceptionLibraryDataInterface[]
     */
    public function getExceptionLibraryData() : array
    {
        return $this->exceptionLibraries;
    }

    /**
     * getLibraryDataFor
     * @param class-string $classString
     * @return ExceptionLibraryDataInterface
     * @throws Throwable
     * @throws \ReflectionException
     */
    protected function getLibraryDataFor(string $classString): ExceptionLibraryDataInterface
    {
        /**
         * reflect the class string and get the namespace, which is / will be the key in the array which holds the
         * ExceptionLibraryData object we will use to get the code and message
         */
        $reflected = new ReflectionClass($classString);
        $namespace = $reflected->getNamespaceName();

        /**
         * if the library has already been loaded, it will be in the array, otherwise go and find in the same
         * directory in which the exception lives
         */
        $library = $this->exceptionLibraries[$namespace] ?? $this->discoverLibraryDataFromClassString($classString);

        /**
         * if the library is still not found, throw an exception
         */
        if (is_null($library)) {
            throw $this->createExceptionFactoryException(ExceptionFactoryMissingLibraryException::class, [$namespace]);
        }
        return $library;
    }

    /**
     * discoverLibraryDataFromClassString is a clumsy but effective method that tries to find the
     * ExceptionLibraryData object for a given class string (meaning that the library data for the class string has
     * not yet been loaded).  It does this by searching for a php file that, when reflected, implements
     * ExceptionLibraryDataInterface.  When it finds it, the class is instantiated and returned.  Returns null if it
     * does not find it, meaning that there is no ExceptionLibraryData object in the library directory that contains
     * the object referred to by the classString argument.
     *
     * @param string $classString
     * @return ExceptionLibraryDataInterface|null
     * @throws \ReflectionException
     */
    protected function discoverLibraryDataFromClassString(string $classString): ?ExceptionLibraryDataInterface
    {
        /**
         * reflect the classString
         */
        $reflected = new ReflectionClass($classString);

        /**
         * get the filename (including path) from the reflection
         */
        $fileName = $reflected->getFileName();

        /**
         * get the directory portion of the filename and scan it for files.
         */
        $dir = pathinfo($fileName, PATHINFO_DIRNAME);
        $files = array_diff(scandir($dir), array('.', '..'));

        /**
         * iterate through the list of files, trying to reflect each one.
         */
        foreach ($files as $file) {
            /**
             * if classname is false then it cannot be reflected.  Conversely, if classname is not false, then it is
             * definitely reflectable, so we don't need to test for reflection failing.
             */
            $className = ExceptionLibraryUtils::getClassStringFromFile($dir . DIRECTORY_SEPARATOR .
                $file);
            if ($file !== false) {
                $reflected = new ReflectionClass($className);
                /**
                 * if it implements the right interface, return a new instance.
                 */
                if ($reflected->implementsInterface(ExceptionLibraryDataInterface::class)) {
                    return new $className();
                }
            }
        }
        return null;
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
        /**
         * validate the class string, throw our own exception if the class string is not reflectable or does not
         * implement \Throwable.
         */
        if (is_null($reflection = ExceptionLibraryUtils::validateExceptionClassString($classString))) {
            throw $this->createExceptionFactoryException(ExceptionFactoryArgumentException::class, [$classString]);
        }

        /**
         * get the exception library for the class string.  Although the index (key) in the exceptionLibraries array
         * is the namespace, loadExceptionLibrary take a class string as an argument because via reflection, we can
         * discover the library in that namespace in the event it has not already been loaded.
         */
        $this->registerExceptionLibraryData($libraryData = $this->getLibraryDataFor($classString));

        /**
         * get the local code and local message for the exception from the library
         */
        $message = $libraryData->getLocalMessage($classString);
        $code = $libraryData->getLocalCode($classString);

        /**
         * usually params can be automatically converted to strings, but if something weird happens like passing an
         * object in as a param, let's be certain vsprintf can deal with it.
         */
        $newParams = [];
        foreach ($params as $param) {
            $newParams[] = (is_scalar($param) ? $param : "<" . gettype($param) . ">");
        }
        $message = vsprintf($message, $newParams);

        /**
         * if code == 0, there is no local code for this exception, and we will just use a code of 0 for the whole
         * thing, i.e. don't bother with trying to get/allocate a library code prefix.  If $code != 0, then we
         * have a local code and we should prefix it with the entry from library codes.
         */
        if ($code != 0) {
            $prefix = (string) $this->libraryCodePrefixes->getLibraryCodePrefix($reflection->getNamespaceName());
            $code = (int)($prefix . $code);
        }

        /** @var Throwable $exception */
        $exception = new $classString($message, $code, $prev);
        return $exception;
    }

    protected function createExceptionFactoryException(string $exceptionClassString, array $params = []):
    Throwable
    {
        $code = $this->getCode($exceptionClassString);
        $message = vsprintf($this->getMessage($exceptionClassString), $params);

        /** @var Throwable $exception */
        $exception = new $exceptionClassString($message, $code);
        return $exception;
    }

    /**
     * getCode
     * @param string $classString
     * @return int
     */
    protected function getCode(string $classString): int
    {
        $codes = [
            ExceptionFactoryArgumentException::class => 1000,
            ExceptionFactoryMissingLibraryException::class => 1001,
        ];
        return $codes[$classString];
    }

    protected function getMessage(string $classString): string
    {
        $messages = [
            ExceptionFactoryArgumentException::class => "Reflection of exception class string (%s) failed.  Either the class is not defined or the class does not implement \Throwable.",
            ExceptionFactoryMissingLibraryException::class => "No exception library found in namespace %s.",
        ];
        return $messages[$classString];
    }
}
