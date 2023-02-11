<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\err\err\XFactoryClassStringArgumentException;
use pvc\err\err\XFactoryMissingXDataException;
use pvc\interfaces\err\XFactoryInterface;
use pvc\interfaces\err\XCodePrefixesInterface;
use pvc\interfaces\err\XDataInterface;
use ReflectionClass;
use Throwable;

/**
 * Top level class used to create exceptions in the pvc libraries.
 *
 *
 * Class ExceptionFactory
 */
class XFactory implements XFactoryInterface
{
    /**
     * @var XCodePrefixesInterface.  Object which manages exception code prefixes and can return one for a given
     * namespace.
     */
    protected XCodePrefixesInterface $xCodePrefixes;

    /**
     * @var array<XDataInterface>
     */
    protected array $exceptionLibraries = [];

    /**
     * @param XCodePrefixesInterface $xCodePrefixes
     */
    public function __construct(XCodePrefixesInterface $xCodePrefixes)
    {
        $this->xCodePrefixes = $xCodePrefixes;
    }

    /**
     * registerXData
     * @param XDataInterface $xData
     */
    public function registerXData(XDataInterface $xData): void
    {
        $this->exceptionLibraries[$xData->getNamespace()] = $xData;
    }

    /**
     * getExceptionLibraryData
     * @return XDataInterface[]
     */
    public function getExceptionLibraryData() : array
    {
        return $this->exceptionLibraries;
    }

    /**
     * getXDataFor
     * @param class-string $classString
     * @return XDataInterface
     * @throws Throwable
     * @throws \ReflectionException
     */
    protected function getXDataFor(string $classString): XDataInterface
    {
        /**
         * reflect the class string and get the namespace, which is / will be the key in the array which holds the
         * exception library data (XData) object we will use to get the code and message
         */
        $reflected = new ReflectionClass($classString);
        $namespace = $reflected->getNamespaceName();

        /**
         * if the library has already been loaded, it will be in the array, otherwise go and find in the same
         * directory in which the exception lives
         */
        $library = $this->exceptionLibraries[$namespace] ?? $this->discoverXDataFromClassString($classString);

        /**
         * if the library is still not found, throw an exception
         */
        if (is_null($library)) {
            throw $this->createException(XFactoryMissingXDataException::class, [$namespace]);
        }
        return $library;
    }

    /**
     * discoverXDataFromClassString is a clumsy but effective method that tries to find the
     * ExceptionLibraryData object for a given class string (meaning that the library data for the class string has
     * not yet been loaded/registered).  It does this by searching for a php file that, when reflected, implements
     * XDataInterface.  When it finds it, the class is instantiated and returned.  Returns null if it
     * does not find it, meaning that there is no ExceptionLibraryData object in the library directory that contains
     * the object referred to by the classString argument.
     *
     * @param class-string $classString
     * @return XDataInterface|null
     * @throws \ReflectionException
     */
    protected function discoverXDataFromClassString(string $classString): ?XDataInterface
    {
        /**
         * reflect the classString
         */
        $reflected = new ReflectionClass($classString);

        /**
         * get the filename (including path) from the reflection.  false is only returned from getFileName if the
         * class is part of php core or defined in an extension, so it should be ok to typehint $fileName.
         * @var string $fileName
         */
        $fileName = $reflected->getFileName();

        /**
         * get the directory portion of the filename and scan it for files.
         * @var string $dir
         */
        $dir = pathinfo($fileName, PATHINFO_DIRNAME);

        /**
         * phpstan does know that scandir cannot return false in this case, so typehint the $files variable
         * @var array<string> $files
         */
        $files = scandir($dir);
        $files = array_diff($files, array('.', '..'));

        /**
         * iterate through the list of files, trying to reflect each one.
         */
        foreach ($files as $file) {
            /** @var class-string $className */
            $className = XLibUtils::getClassStringFromFile($dir . DIRECTORY_SEPARATOR . $file);
            if ($className !== false) {
                $reflected = new ReflectionClass($className);
                /**
                 * if it implements the right interface, return a new instance.
                 */
                if ($reflected->implementsInterface(XDataInterface::class)) {
                    /** @var XDataInterface $xData */
                    $xData = new $className();
                    return $xData;
                }
            }
        }
        /**
         * if we got to here, we've iterated through the directory without finding a file that has the right interface.
         */
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
        if (is_null($reflection = XLibUtils::validateExceptionClassString($classString))) {
            throw $this->createException(XFactoryClassStringArgumentException::class, [$classString]);
        }

        /**
         * get the exception library for the class string.  Although the index (key) in the exceptionLibraries array
         * is the namespace, loadExceptionLibrary take a class string as an argument because via reflection, we can
         * discover the library in that namespace in the event it has not already been loaded.
         */
        $this->registerXData($libraryData = $this->getXDataFor($classString));

        /**
         * get the local code and local message for the exception from the library
         */
        $message = $libraryData->getLocalXMessage($classString);
        $code = $libraryData->getLocalXCode($classString);

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
            $prefix = (string) $this->xCodePrefixes->getXCodePrefix($reflection->getNamespaceName());
            $code = (int)($prefix . $code);
        }

        /** @var Throwable $exception */
        $exception = new $classString($message, $code, $prev);
        return $exception;
    }
}
