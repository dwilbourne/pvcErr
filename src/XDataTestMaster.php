<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use PHPUnit\Framework\TestCase;
use pvc\err\stock\Exception;
use pvc\interfaces\err\XDataInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use Throwable;

/**
 * Class XDataTestMaster
 */
class XDataTestMaster extends TestCase
{
    /**
     * verifyLibrary
     * @param XDataInterface $xData
     */
    public function verifyLibrary(XDataInterface $xData): bool
    {
        $result = $this->verifyKeysMatchClassStringsFromDir($xData);
        $result = $result && $this->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData);
        $result = $result && $this->verifyGetLocalMessagesArrayHasStringsForValues($xData);
        $result = $result && $this->verifyExceptionParametersAndMessageParametersMatch($xData);
        $result = $result && $this->verifyExceptionsCanBeInstantiated($xData);
        return $result;
    }

    public function verifyKeysMatchClassStringsFromDir(XDataInterface $xData): bool
    {
        $codesArray = $xData->getLocalXCodes();
        $messagesArray = $xData->getXMessageTemplates();
        $exceptionClassStrings = $this->getExceptionClassStrings($xData);
        $keysForCodes = array_keys($codesArray);
        $keysForMessages = array_keys($messagesArray);

        $result = true;

        $keysForCodesThatHaveNoExceptionDefined = array_diff($keysForCodes, $exceptionClassStrings);
        if (!empty($keysForCodesThatHaveNoExceptionDefined)) {
            foreach ($keysForCodesThatHaveNoExceptionDefined as $key) {
                echo sprintf("codes key %s has no corresponding exception defined.\n", $key);
            }
            $result = false;
        }


        $keysForMessagesThatHaveNoExceptionDefined = array_diff($keysForMessages, $exceptionClassStrings);
        if (!empty($keysForMessagesThatHaveNoExceptionDefined)) {
            foreach ($keysForMessagesThatHaveNoExceptionDefined as $key) {
                echo sprintf("messages key %s has no corresponding exception defined.\n", $key);
            }
            $result = false;
        }

        $exceptionsWithNoCodeDefined = array_diff($exceptionClassStrings, $keysForCodes);
        if (!empty($exceptionsWithNoCodeDefined)) {
            foreach ($exceptionsWithNoCodeDefined as $key) {
                echo sprintf("exception %s has no corresponding code defined.\n", $key);
            }
            $result = false;
        }

        $exceptionsWithNoMessageDefined = array_diff($exceptionClassStrings, $keysForMessages);
        if (!empty($exceptionsWithNoMessageDefined)) {
            foreach ($exceptionsWithNoMessageDefined as $key) {
                echo sprintf("exception %s has no corresponding message defined.\n", $key);
            }
            $result = false;
        }

        return $result;
    }

    /**
     * getExceptionClassStringsFromDir
     * @return array<int, class-string>
     */
    public function getExceptionClassStrings(XDataInterface $xData): array
    {
        /**
         * reflect XData and get the directory portion of the file name
         */
        $reflectedXData = new ReflectionClass($xData);
        $filePath = $reflectedXData->getFileName() ?: '';
        $dir = pathinfo($filePath, PATHINFO_DIRNAME);

        /**
         * put all the files from the directory into an array, removing any directory entries.  Typehint files so
         * phpstan does not complain. scandir returns false if its argument is not a directory, but in this case that
         * cannot be true because the directory is pulled via pathinfo.
         */
        /** @var array<int, string> $files */
        $files = scandir($dir);
        foreach ($files as $index => $file) {
            if (is_dir($file)) {
                unset($files[$index]);
            }
        }

        /**
         * initialize the private array
         */
        $classStrings = [];

        foreach ($files as $file) {
            /**
             * get the class string by parsing the file
             * @var class-string $classString
             */
            $fileContents = file_get_contents($dir . DIRECTORY_SEPARATOR . $file);
            $classString = Exception::getClassStringFromFileContents($fileContents);

            /**
             * validate the class string:  must be reflectable (i.e. an object) and must implement Throwable.  If
             * it is valid, add it to our array of exceptions in the library
             */
            try {
                $reflectedFile = new ReflectionClass($classString);
                if ($reflectedFile->implementsInterface(Throwable::class)) {
                    $classStrings[] = $classString;
                }
            } catch (ReflectionException $e) {
                /** either reflection failed or it was not Throwable */
            }
        }

        return $classStrings;
    }

    /**
     * verifyGetLocalCodesArrayHasUniqueIntegerValues
     * @param XDataInterface $xData
     * @return bool
     */
    public function verifyGetLocalCodesArrayHasUniqueIntegerValues(XDataInterface $xData): bool
    {
        $codesArray = $xData->getLocalXCodes();
        $result = true;
        /**
         * verify that the count of unique codes equals the total count of codes
         */
        if (count(array_unique($codesArray)) != count($codesArray)) {
            echo sprintf("not all exception codes are unique.\n");
            $result = false;
        }

        /**
         * verify $codes is all integers.
         * if $codes is empty, $initialValue will be false and then array_reduce returns false.
         */
        $initialValue = !empty($codesArray);
        $callback = function ($carry, $x) {
            return ($carry && is_int($x));
        };
        if (false == (array_reduce($codesArray, $callback, $initialValue))) {
            echo sprintf("not all exception codes are integers.\n");
            $result = false;
        }
        return $result;
    }

    /**
     * this method is borrowed from the pvc\err\pvc\Exception class.  Originally, I even
     * had a separate utilities class which provided the code that could be shared.  But in the interests of
     * keeping the publicly available methods as few as possible, I chose to duplicate the code here....
     */

    public function verifyGetLocalMessagesArrayHasStringsForValues(XDataInterface $xData): bool
    {
        $messagesArray = $xData->getXMessageTemplates();
        $result = true;
        /**
         * verify $messages is all strings
         * if messages array is empty, $initialValue will be false and then array_reduce returns false.
         */
        $initialValue = !empty($messagesArray);
        $callback = function ($carry, $x) {
            return ($carry && is_string($x));
        };
        if (false == (array_reduce($messagesArray, $callback, $initialValue))) {
            echo sprintf("not all exception messages are strings.\n");
            $result = false;
        }
        return $result;
    }

    /**
     * verifyExceptionParametersAndMessageParametersMatch
     * @param XDataInterface $xData
     * @return bool
     * @throws ReflectionException
     */
    public function verifyExceptionParametersAndMessageParametersMatch(XDataInterface $xData): bool
    {
        $result = true;
        foreach ($this->getExceptionClassStrings($xData) as $classString) {
            $reflected = new ReflectionClass($classString);
            $reflectionParams = $reflected->getConstructor()->getParameters();
            $countOfParams = count($reflectionParams);
            /**
             * all exceptions must have at least one parameter
             */
            if ($countOfParams == 0) {
                echo sprintf("%s has no parameters and must have at least a \$prev parameter.\n", $classString);
                $result = false;
                /**
                 * need to break from the loop because the rest of these tests depend on there being at least one
                 * parameter
                 */
                break;
            }
            /**
             * ensure that last param is Throwable
             */
            $lastParam = $reflectionParams[$countOfParams - 1];
            if (!$this->parameterIsThrowable($lastParam)) {
                echo sprintf("The last parameter (e.g. \$prev) of %s is not Throwable.\n", $classString);
                $result = false;
            }
            /**
             * ensure that the last parameter has a default of null
             */
            if (!$this->parameterHasDefaultValueOfNull($lastParam)) {
                $format = "The last parameter (e.g. \$prev) of %s does not have a default value of null.\n";
                echo sprintf($format, $classString);
                $result = false;
            }

            /**
             * bump the last parameter ($prev) off the array.
             */
            array_pop($reflectionParams);

            /**
             * verify that the parameter names all match the variable names in the messages.  Variable names in the
             * messages do NOT have to be in the same order as they appear in the constructor declaration for the
             * exception.
             */
            $paramNames = [];
            foreach ($reflectionParams as $param) {
                $paramNames[] = $param->getName();
            }

            $messageVariableNames = $this->parseVariableNamesFromMessage($xData->getXMessageTemplate($classString));

            sort($paramNames);
            sort($messageVariableNames);

            if ($paramNames != $messageVariableNames) {
                $format = "The parameters of %s do not match the variable names in the corresponding message.\n";
                echo sprintf($format, $classString);
                $result = false;
            }
        }
        /** end of foreach loop through the class strings */
        return $result;
    }

    /**
     * verifyExceptionsCanBeInstantiated
     * @param XDataInterface $xData
     * @return bool
     * @throws ReflectionException
     */
    public function verifyExceptionsCanBeInstantiated(XDataInterface $xData): bool
    {
        $result = true;
        foreach ($this->getExceptionClassStrings($xData) as $classString) {
            $reflected = new ReflectionClass($classString);
            $reflectionParams = $reflected->getConstructor()->getParameters();

            /**
             * create array of dummy parameters, so we can instantiate the class.  Do not create a dummy parameter
             * for the $prev parameter, which has been tested separately, and we know is both Throwable and has a
             * default of null
             */
            array_pop($reflectionParams);
            $paramValues = [];
            foreach ($reflectionParams as $param) {
                $typeName = $this->getReflectionTypeName($param->getType());
                $paramValues[] = $this->createDummyParamValueBasedOnType($typeName);
            }

            /**
             * verify that we can instantiate the exception class so the constructor is exercised.  Then we can
             * claim 100% line coverage in the testing
             */
            $instance = $reflected->newInstanceArgs($paramValues);
            $result = $result && ($instance instanceof $classString);
        }
        return $result;
    }

    public function parseVariableNamesFromMessage(string $message): array
    {
        /**
         * looking for any group of non-whitespace characters starting with '${' and ending with '}'.  The capturing
         * subpattern puts the subpattern matches into the $matches[1]
         */
        $pattern = '/\$\{(\S*)}/';
        $result = preg_match_all($pattern, $message, $matches);
        /**
         * preg_match_all returns false on failure, or the number of matches it found (could be zero).  So, if it
         * found a non-zero number of matches, return the captured subpatterns (everything in the matches array
         * except the first element) or else return an empty array.
         */
        return $result ? $matches[1] : [];
    }

    /**
     * parameterIsThrowable
     * @param ReflectionParameter $param
     * @return bool
     */
    public function parameterIsThrowable(ReflectionParameter $param): bool
    {

        $reflectionType = $param->getType();

        /**
         * with PHP 8+ ReflectionType has 3 subtypes in order to accommodate intersection and union types.  Because
         * we are looking for Throwable only (e.g. a named type, not an intersection or a union), we can test for
         * ReflectionNamedType.
         */
        if (is_null($reflectionType) || (false == ($reflectionType instanceof ReflectionNamedType))) {
            return false;
        }

        return ($reflectionType->getName() == 'Throwable');
    }

    /**
     * parameterHasDefaultValueOfNull
     * @param ReflectionParameter $param
     * @return bool
     * @throws ReflectionException
     */
    public function parameterHasDefaultValueOfNull(ReflectionParameter $param): bool
    {
        return ($param->isOptional() && (null == $param->getDefaultValue()));
    }

    /**
     * createDummyParamValueBasedOnType
     * @param \ReflectionType $paramType
     * @return int|string|true
     *
     * In the event that the method parameter is untyped, $paramType will be null.
     *
     * Of not null, ReflectionType is actually an instance of one of three kinds of subtypes: ReflectionNamedType,
     * ReflectionUnionType, and ReflectionIntersectionType, which is in keeping with the addition of union and
     * intersection data types in parameter declarations.  For union and intersection, we get an array of
     * reflection types via the getTypes method.  In both cases we can simply use the first member of the
     * array as a suitable candidate.  Because PHP does not support abstract data types per se, we do not need to
     * recurse.  We know that the types inside the array must be bool|int|float|string|array|resource|object.
     */
    public function createDummyParamValueBasedOnType(string $paramType)
    {
        switch ($paramType) {
            case 'string':
                return 'foo';
                break;
            /**
             * evidently, the type can be either int or integer.........
             */
            case 'integer':
            case 'int':
                return 5;
                break;
            case 'bool':
                return true;
                break;
            default:
                return '{' . $paramType . '}';
                break;
        }
    }

    /**
     * getReflectionNamedType
     * @param \ReflectionType|null $paramType
     * @return string
     * returns a base datatype suitable for creating a dummy parameter value
     */
    public function getReflectionTypeName(?\ReflectionType $paramType): string
    {
        /**
         * if the parameter is untyped, put in a string.
         */
        if (is_null($paramType)) {
            return 'string';
        }

        /**
         * if it's a named type, return the name
         */
        if ($paramType instanceof \ReflectionNamedType) {
            return $paramType->getName();
        }

        /**
         * if it's an intersection or union type, pick the first element in the array
         */
        $typesArray = $paramType->getTypes();
        $type = $typesArray[0];
        return $type->getName();
    }
}
