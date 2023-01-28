<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvc\err;

use PHPStan\Type\IterableType;
use pvc\err\err\LibraryCodeArrayElementIsInvalidException;
use pvc\err\err\LibraryCodeFileNotReadableException;
use pvc\err\err\LibraryCodeFileNotParseableJsonException;
use pvc\err\err\LibraryCodeFileNotWriteableException;
use pvc\err\err\LibraryCodeValueAlreadyInUseException;
use pvc\interfaces\err\ExceptionLibraryCodePrefixesInterface;
use Throwable;

/**
 * Class responsible for library codes
 *
 * This object helps ExceptionFactory create globally unique error codes for your
 * exceptions.  See the README.md file for the guide on how to use this class in conjunction with
 * ExceptionFactory.
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
 * The getLibraryCodePrefix method takes a class string as an argument.  It will reflect the class string and get the
 * namespace of the class. If there is a key in the library codes array corresponding to the namespace, getlibraryCode
 * just returns the already allocated code.  If getLibraryCodePrefix is called with a class string which resides in a
 * namespace which has not yet been assigned a library code, it assigns the next code, writes the array back to the
 * json file and returns the new code.
 *
 * Class LibraryCodes
 */
class ExceptionLibraryCodePrefixes implements ExceptionLibraryCodePrefixesInterface
{
    /**
     * @var array<string, int> $libraryCodes .  Map of namespaces to exception library codes
     */
    protected array $libraryCodes = [];

    /**
     * @var string $libraryCodesFilePath . Location where you keep your library codes json file.
     */
    protected string $libraryCodesFilePath;

    /**
     * @var string.  Default library codes file if none is supplied to the class constructor.
     */
    protected string $defaultLibraryCodesFilePath = __DIR__ . "/librarycodes.json";

    /**
     * @var int
     */
    protected int $initialLibraryCodeValue = 1001;

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
        $codes = $this->parseLibraryCodePrefixesFile($libraryCodesFilePath);

        /**
         * add each namespace => library code pair to the array
         */
        foreach ($codes as $namespace => $value) {
            $this->addLibraryCodePrefix($namespace, $value);
        }

        /**
         * don't set this attribute until the file has been parsed and the types have been verified
         */
        $this->libraryCodesFilePath = $libraryCodesFilePath;
    }

    /**
     * parse the library codes file into an array which is properly structured and typed.
     *
     * @function parseLibraryCodePrefixesFile
     * @param string $file
     * @return array<string, int>
     * @throws \Throwable
     */
    protected function parseLibraryCodePrefixesFile(string $file): array
    {
        $codes = [];
        /**
         * if file does not exist, just return an empty array.
         */
        if (!file_exists($file)) {
            return $codes;
        }

        if (!is_readable($file)) {
            throw $this->createLibraryCodesException(LibraryCodeFileNotReadableException::class, [$file]);
        }

        /** @var string $fileContents */
        $fileContents = file_get_contents($file);

        /**
         * json_decode will return an associative array
         */
        $associative = true;
        /** @var array<string, int> $codes */
        $codes = json_decode($fileContents, $associative);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw $this->createLibraryCodesException(LibraryCodeFileNotParseableJsonException::class, [$file]);
        }

        /**
         * makes sure each pair is string => integer
         */
        foreach ($codes as $namespace => $value) {
            if ((!is_string($namespace) || (!is_int($value)))) {
                throw $this->createLibraryCodesException(
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
     * @function addLibraryCodePrefix
     * @param string $namespace
     * @param int $prefix
     * @throws \Throwable
     */
    protected function addLibraryCodePrefix(string $namespace, int $prefix): void
    {
        /**
         * throw an exception if the value is already in use.
         */
        if ($namespaceAlreadyUsingPrefix = array_search($prefix, $this->libraryCodes)) {
            throw $this->createLibraryCodesException(LibraryCodeValueAlreadyInUseException::class, [$prefix,
                $namespaceAlreadyUsingPrefix]);
        }
        $this->libraryCodes[$namespace] = $prefix;
    }

    /**
     * @function getLibraryCodePrefixes
     * @return array<string, int>
     */
    public function getLibraryCodePrefixes(): array
    {
        return $this->libraryCodes;
    }

    /**
     * getNextLibraryCodePrefix.  Finds the largest library code in the array and returns one more than that.
     * @return int
     */
    protected function getNextLibraryCodePrefix(): int
    {
        if (empty($this->getLibraryCodePrefixes())) {
            return $this->initialLibraryCodeValue;
        } else {
            return (1 + max($this->getLibraryCodePrefixes()));
        }
    }

    /**
     * returns the library code for an exception class string, allocating a new one if necessary.  Class referred to by
     * class string must be defined and must implement \Throwable.
     *
     * getLibraryCodePrefix
     * @param string $namespace
     * @return int
     */
    public function getLibraryCodePrefix(string $namespace): int
    {
        /**
         * if namespace already has a code then assign that value to $code
         */
        if (isset($this->libraryCodes[$namespace])) {
            $code = $this->libraryCodes[$namespace];
        /**
         * otherwise, get the next code, add the namespace / code pair to the array and write the array back to disk.
         */
        } else {
            $code = $this->getNextLibraryCodePrefix();
            $this->libraryCodes[$namespace] = $code;
            if (false === file_put_contents($this->libraryCodesFilePath, json_encode($this->getLibraryCodePrefixes())
                )) {
                throw $this->createLibraryCodesException(LibraryCodeFileNotWriteableException::class,
                    [$this->libraryCodesFilePath]);
            }
        }

        return $code;
    }

    protected function createLibraryCodesException(string $exceptionClassString, array $params = []) :
    Throwable
    {
        $code = $this->getCode($exceptionClassString);
        $message = vsprintf($this->getMessage($exceptionClassString), $params);

        /** @var Throwable $exception */
        $exception = new $exceptionClassString($message, $code);
        return $exception;
    }


    /**
     * exception codes and messages for the ExceptionLibraryCodes object. The codes in here presume a "prefix"
     * of 1000 and this object starts allocating prefixes at 1001.  This object uses a traditional method for
     * throwing exceptions in order to avoid circular dependencies.  In other words, we cannot use an exception
     * factory here to throw exceptions because the exception factories depend on this object in order to create
     * exceptions.
     */

    /**
     * getCode
     * @param string $classString
     * @return int
     */
    protected function getCode(string $classString) : int
    {
        $codes =  [
            LibraryCodeFileNotReadableException::class => 10001001,
            LibraryCodeFileNotParseableJsonException::class => 10001002,
            LibraryCodeArrayElementIsInvalidException::class => 10001003,
            LibraryCodeValueAlreadyInUseException::class => 10001004,
            LibraryCodeFileNotWriteableException::class => 10001006,
        ];
        return $codes[$classString] ?? 0;
    }

    /**
     * getMessage
     * @param string $classString
     * @return string
     */
    protected function getMessage(string $classString) : string
    {
        $messages = [
            LibraryCodeFileNotReadableException::class => 'Library code file argument %s is not readable or does not exist.',
            LibraryCodeFileNotParseableJsonException::class => 'Library code file argument %s is not a parseable json file',
            LibraryCodeArrayElementIsInvalidException::class => 'Key value pair [%s, %s] invalid: must be <string, int>.',
            LibraryCodeValueAlreadyInUseException::class => 'Library code %s is already in use by namespace %s.',
            LibraryCodeFileNotWriteableException::class => "Library code file %s is not writeable.",
        ];
        return $messages[$classString] ?? "";
    }

}
