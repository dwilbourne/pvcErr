<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\err\err\_LibraryCodesExceptionFactory;
use pvc\err\err\InvalidExceptionClassStringException;
use pvc\err\err\LibraryCodeArrayElementIsInvalidException;
use pvc\err\err\LibraryCodeFileDoesNotParseToAnArrayException;
use pvc\err\err\LibraryCodeFileGetContentsException;
use pvc\err\err\LibraryCodeFileNotParseableJsonException;
use pvc\err\err\LibraryCodeFileNotWriteableException;
use pvc\err\err\LibraryCodeValueAlreadyInUseException;
use pvc\err\stock\ReflectionException;
use pvc\interfaces\err\ExceptionLibraryCodesInterface;
use ReflectionClass;
use Throwable;

/**
 * Class responsible for library codes
 *
 * This object helps ExceptionFactoryAbstract create globally unique error codes for your
 * exceptions.  See the README.md file for the guide on how to use this class in conjunction with
 * ExceptionFactoryAbstract.
 *
 * This class keeps an array of exceptionNamespace => libraryCode pairs.  An exceptionNamespace is the namespace of
 * an exception library, which is a directory containing exceptions plus an exception factory which is used to create
 * the exceptions in that library.  The libraryCode is a unique integer value which is used as a prefix to local
 * exception codes from a particular exception library.  As long as local exception codes are unique within a
 * library, this prefixing mechanism will ensure that full exception codes are globally unique.
 *
 * Persistence is handled using a json file to store the library codes in between requests.  The constructor to this
 * class takes a filepath string which tells the constructor where to find the json file which contains namespaces
 * which have already been registered for the application.  If no argument is supplied to the constructor, the file
 * will be created in the same directory in which this class resides with a default filename.  For your application,
 * a config directory or perhaps in the src directory itself would be natural places to keep this file.
 *
 * This class is hardcoded to allocate namespace library codes starting at 1000 in increments of 1.
 *
 * The getLibraryCode method takes a class string as an argument.  It will reflect the class string and get the
 * namespace of the class. If there is a key in the library codes array corresponding to the namespace, getlibraryCode
 * just returns the already allocated code.  If getLibraryCode is called with a class string which resides in a
 * namespace which has not yet been assigned a library code, it assigns the next code, writes the array back to the
 * json file and returns the new code.
 *
 * Class LibraryCodes
 */
class ExceptionLibraryCodes implements ExceptionLibraryCodesInterface
{
    /**
     * @var array<string, int> $libraryCodes.  Map of namespaces to exception library codes
     */
    protected array $libraryCodes = [];

    /**
     * @var string $libraryCodesFilePath. Location where you keep your library codes json file.
     */
    protected string $libraryCodesFilePath;

    /**
     * @var _LibraryCodesExceptionFactory $exceptionFactory.  Used to throw exceptions in this class.
     */
    protected _LibraryCodesExceptionFactory $exceptionFactory;

    /**
     * @var string.  Default library codes file if none is supplied to the class constructor.
     */
    protected string $defaultLibraryCodesFilePath = __DIR__ . "/librarycodes.json";

    /**
     * @var int
     */
    protected int $initialLibraryCodeValue = 1000;

    /**
     * @param string|null $libraryCodesFilePath .
     * @throws Throwable
     */
    public function __construct(string $libraryCodesFilePath = null)
    {
        /**
         * use the default filepath if not supplied in the constructor argument
         */
        $libraryCodesFilePath = $libraryCodesFilePath ?? $this->defaultLibraryCodesFilePath;

        /**
         * parse the namespace / code pairs into a local variable
         */
        $codes = $this->parseLibraryCodesFile($libraryCodesFilePath);

        /**
         * don't set this attribute until the file has been parsed and the types have been verified
         */
        $this->libraryCodesFilePath = $libraryCodesFilePath;

        /**
         * add each namespace => library code pair to the array
         */
        foreach ($codes as $namespace => $value) {
            $this->addLibraryCode($namespace, $value);
        }
    }

    /**
     * parse the library codes file into an array which is properly structured and typed.  If the file does not exist
     * yet, create it as an empty file.
     *
     * @function parseLibraryCodesFile
     * @param string $file
     * @return array<string, int>
     * @throws \Throwable
     */
    protected function parseLibraryCodesFile(string $file): array
    {
        /**
         * the library codes file must be writeable as well as readable because it gets written back to disk when new
         * namespace => codes are added to the array.  Checking for it now instead of waiting until later.......
         *
         * If the file exists, make sure it is writeable.  If it does not exist, create an empty file to ensure we
         * can write to it later.
         */
        if (file_exists($file) && !is_writable($file)) {
            throw $this->exceptionFactory->createException(
                LibraryCodeFileNotWriteableException::class,
                [$this->libraryCodesFilePath]
            );
        }

        if (!file_exists($file)) {
            if (false === ($fp = fopen($file, "w+"))) {
                throw $this->exceptionFactory->createException(
                    LibraryCodeFileNotWriteableException::class,
                    [$this->libraryCodesFilePath]
                );
            }
            fclose($fp);
        }

        if (false === ($fileContents = file_get_contents($file))) {
            throw $this->exceptionFactory->createException(
                LibraryCodeFileGetContentsException::class,
                [$file]
            );
        }

        $associative = true;
        if (false === ($codes = json_decode($fileContents, $associative))) {
            throw $this->exceptionFactory->createException(
                LibraryCodeFileNotParseableJsonException::class,
                [$file]
            );
        }

        if (!is_array($codes)) {
            throw $this->exceptionFactory->createException(
                LibraryCodeFileDoesNotParseToAnArrayException::class,
                [$file]
            );
        }

        foreach ($codes as $namespace => $value) {
            if ((!is_string($namespace) || (!is_int($value)))) {
                throw $this->exceptionFactory->createException(
                    LibraryCodeArrayElementIsInvalidException::class,
                    [$namespace, $value]
                );
            }
        }
        return $codes;
    }

    /**
     * adds a namespace => code pair to the library codes repository as long as neither the value is not already in use
     *
     * @function addLibraryCode
     * @param string $namespace
     * @param int $code
     * @throws \Throwable
     */
    protected function addLibraryCode(string $namespace, int $code): void
    {
        /**
         * throw an exception if the value is already in use.  Of course, because they are keys in the array,
         * namespaces must already be unique, so no need to test for that.
         */
        if (in_array($code, $this->libraryCodes)) {
            $flipped = array_flip($this->getLibraryCodes());
            $namespace = $flipped[$code];
            throw $this->exceptionFactory->createException(LibraryCodeValueAlreadyInUseException::class, [$code,
                $namespace]);
        }
        $this->libraryCodes[$namespace] = $code;
    }

    /**
     * @function getLibraryCodes
     * @return array<string, int>
     */
    public function getLibraryCodes(): array
    {
        return $this->libraryCodes;
    }

    /**
     * getNextLibraryCode.  Finds the largest library code in the array and returns one more than that.
     * @return int
     */
    protected function getNextLibraryCode(): int
    {
        if (empty($this->getLibraryCodes())) {
            return $this->initialLibraryCodeValue;
        } else {
            return (1 + max($this->getLibraryCodes()));
        }
    }

    /**
     * returns the library code for an exception class string, allocating a new one if necessary.  Class referred to by
     * class string must be defined and must implement \Throwable.
     *
     * getLibraryCode
     * @param class-string $classString
     * @return int
     */
    public function getLibraryCode(string $classString): int
    {
        try {
            /**
             * this will throw an exception if the string is not reflectable.
             */
            $reflection = new ReflectionClass($classString);
        /**
         * phpstan get a little confused here.  It wants you tpo properly typehint $classString as a class-string
         * and then when you do that, complains that the catch clause is dead because a class string can always
         * be reflected......
         *
         * @phpstan-ignore-next-line
         */
        } catch (ReflectionException $e) {
            $this->exceptionFactory->createException(InvalidExceptionClassStringException::class, [$classString]);
        }

        /**
         * throw an exception if the class is not throwable
         */
        if (!$reflection->implementsInterface(Throwable::class)) {
            $this->exceptionFactory->createException(InvalidExceptionClassStringException::class, [$classString]);
        }

        $namespace = $reflection->getNamespaceName();

        /**
         * if namespace already has a code then assign that value to $code
         */
        if (isset($this->libraryCodes[$namespace])) {
            $code = $this->libraryCodes[$namespace];
        /**
         * otherwise, get the next code, add the namespace / code pair to the array and write the array back to disk.
         */
        } else {
            $code = $this->getNextLibraryCode();
            $this->libraryCodes[$namespace] = $code;
            file_put_contents($this->libraryCodesFilePath, json_encode($this->getLibraryCodes()));
        }

        return $code;
    }
}
