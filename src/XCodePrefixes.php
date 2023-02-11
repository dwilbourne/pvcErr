<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvc\err;

use pvc\err\err\XCodePrefixesFileNotReadableWriteableException;
use pvc\err\err\XCodePrefixesFileNotParseableJsonException;
use pvc\err\err\XCodePrefixAlreadyInUseException;
use pvc\interfaces\err\XCodePrefixesInterface;
use Throwable;

/**
 * Class responsible for exception code prefixes
 *
 * This object helps ExceptionFactory create globally unique error codes for your
 * exceptions.  See the README.md file for the guide on how to use this class in conjunction with
 * ExceptionFactory.
 *
 * This class keeps an array of exceptionNamespace => exceptionPrefix pairs.  An exceptionNamespace is the namespace of
 * an exception library, which is a directory containing exceptions plus an exception data file which is used to create
 * the exceptions in that library.  The exceptionPrefix is a unique integer value which is used as a prefix to local
 * exception codes from a particular exception library.  As long as local exception codes are unique within a
 * library, this prefixing mechanism will ensure that full exception codes are globally unique.
 *
 * Persistence is handled using a json file to store the library codes in between requests.  The constructor to this
 * class takes a filepath string which tells the constructor where to find the json file which contains namespaces
 * which have already been registered for the application.  If no argument is supplied to the constructor, the file
 * will be created in the same directory in which this class resides with a default filename.  For your application,
 * a config directory or perhaps in the src directory itself would be natural places to keep this file.
 *
 * This class is hardcoded to allocate namespace library codes starting at 1000 in increments of 1.  Modify the class
 * to suit your needs as you see fit.
 *
 * The getXCodePrefix method takes a class string as an argument.  It will reflect the class string and get the
 * namespace of the class. If there is a key in the library codes array corresponding to the namespace, getexceptionPrefix
 * just returns the already allocated code.  If getXCodePrefix is called with a class string which resides in a
 * namespace which has not yet been assigned an exception prefix, it assigns the next prefix, writes the array back to
 * the json file and returns the new prefix.
 *
 * Class XCodePrefixes
 */
class XCodePrefixes implements XCodePrefixesInterface
{
    /**
     * @var array<string, int> $xPrefixes .  Map of namespaces to exception library codes
     */
    protected array $xPrefixes = [];

    /**
     * @var string $xPrefixesFilePath . Location where you keep your prefixes json file.
     */
    protected string $xPrefixesFilePath;

    /**
     * @var string.  Default prefixes file if none is supplied to the class constructor.
     */
    protected string $defaultXPrefixesFilePath = __DIR__ . "/XCodePrefixes.json";

    /**
     * @var int
     */
    protected int $initialPrefix = 1001;

    /**
     * @var int
     */
    protected int $prefixIncrement = 1;

    /**
     * @function __construct
     * @param string|null $prefixesFilePath .
     * @throws Throwable
     */
    public function __construct(string $prefixesFilePath = null)
    {
        /**
         * use the default filepath if not supplied in the constructor argument
         */
        $prefixesFilePath = $prefixesFilePath ?? $this->defaultXPrefixesFilePath;

        /**
         * parse the namespace / prefix pairs into a local variable
         */
        $codes = $this->parsePrefixesFile($prefixesFilePath);

        /**
         * add each namespace => prefix pair to the array
         */
        foreach ($codes as $namespace => $value) {
            $this->addPrefix($namespace, $value);
        }

        /**
         * don't set this attribute until the file has been parsed and the types have been verified
         */
        $this->xPrefixesFilePath = $prefixesFilePath;
    }

    /**
     * parse the prefixes file into an array which is properly structured and typed.
     *
     * @function parsePrefixesFile
     * @param string $file
     * @return array<string, int>
     * @throws \Throwable
     */
    protected function parsePrefixesFile(string $file): array
    {
        $codes = [];
        /**
         * if file does not exist, just return an empty array.
         */
        if (!file_exists($file)) {
            return $codes;
        }

        if (!is_readable($file)) {
            throw $this->createPrefixingException(XCodePrefixesFileNotReadableWriteableException::class, [$file]);
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
            throw $this->createPrefixingException(XCodePrefixesFileNotParseableJsonException::class, [$file]);
        }

        return $codes;
    }

    /**
     * adds a namespace => code pair to the library codes repository as long as neither the value is not already in use
     *
     * @function addPrefix
     * @param string $namespace
     * @param int $prefix
     * @throws \Throwable
     */
    protected function addPrefix(string $namespace, int $prefix): void
    {
        /**
         * throw an exception if the value is already in use.
         */
        if ($namespaceAlreadyUsingPrefix = array_search($prefix, $this->xPrefixes)) {
            throw $this->createPrefixingException(XCodePrefixAlreadyInUseException::class, [$prefix,
                $namespaceAlreadyUsingPrefix]);
        }
        $this->xPrefixes[$namespace] = $prefix;
    }

    /**
     * gets all the exception code prefixes that have been registered
     *
     * @function getXCodePrefixes
     * @return array<string, int>
     */
    public function getXCodePrefixes(): array
    {
        return $this->xPrefixes;
    }

    /**
     * Finds the largest prefix in the array and returns one more than that.
     *
     * @function getNextXCodePrefix.
     * @return int
     */
    protected function getNextXCodePrefix(): int
    {
        if (empty($this->getXCodePrefixes())) {
            return $this->initialPrefix;
        } else {
            return (max($this->getXCodePrefixes()) + $this->prefixIncrement);
        }
    }

    /**
     * returns the prefix for an exception namespace, allocating a new one if necessary.
     *
     * @function getXCodePrefix
     * @param string $namespace
     * @return int
     */
    public function getXCodePrefix(string $namespace): int
    {
        /**
         * if namespace already has a code then assign that value to $code
         */
        if (isset($this->xPrefixes[$namespace])) {
            $code = $this->xPrefixes[$namespace];
        /**
         * otherwise, get the next code, add the namespace / code pair to the array and write the array back to disk.
         */
        } else {
            $code = $this->getNextXCodePrefix();
            $this->xPrefixes[$namespace] = $code;
            try {
                file_put_contents($this->xPrefixesFilePath, json_encode($this->getXCodePrefixes()));
            }
            catch (Throwable $e) {
                throw $this->createPrefixingException(XCodePrefixesFileNotReadableWriteableException::class,
                    [$this->xPrefixesFilePath]);
            }
        }

        return $code;
    }

    /**
     * @function createPrefixingException
     * @param string $exceptionClassString
     * @param array<mixed> $params
     * @return Throwable
     */
    protected function createPrefixingException(string $exceptionClassString, array $params = []) :
    Throwable
    {
        $code = $this->getCode($exceptionClassString);
        $message = vsprintf($this->getMessage($exceptionClassString), $params);

        /** @var Throwable $exception */
        $exception = new $exceptionClassString($message, $code);
        return $exception;
    }


    /**
     * exception codes and messages for this class. The codes in here presume a "prefix"
     * of 1000 and this object starts allocating prefixes at 1001.  This object uses a traditional method for
     * throwing exceptions in order to avoid circular dependencies.  In other words, we cannot use an exception
     * factory here to throw exceptions because exception factories depend on this object in order to create
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
            XCodePrefixesFileNotReadableWriteableException::class => 10001001,
            XCodePrefixesFileNotParseableJsonException::class => 10001002,
            XCodePrefixAlreadyInUseException::class => 10001003,
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
            XCodePrefixesFileNotReadableWriteableException::class => 'Prefixes file argument %s is not readable/writeable or does not exist.',
            XCodePrefixesFileNotParseableJsonException::class => 'Prefixes file argument %s is not a parseable json file',
            XCodePrefixAlreadyInUseException::class => 'Prefix %s is already in use by namespace %s.',
        ];
        return $messages[$classString] ?? '';
    }

}
