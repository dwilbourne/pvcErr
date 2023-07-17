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
use ReflectionType;
use Throwable;

/**
 * Class XDataTestMaster
 */
class XDataTestMaster extends TestCase
{
    /**
     * verifyLibrary
     * @param XDataInterface $xData
     * @throws ReflectionException
     * @throws ReflectionException
     */
    public function verifyLibrary(XDataInterface $xData): bool
    {
        /** @var array<class-string<Throwable>> $throwableClassStrings */
        $throwableClassStrings = $this->getThrowableClassStrings($xData);

        $result = $this->verifyXDataKeysMatchClassStringsFromDir($xData, $throwableClassStrings);
        $result = $result && $this->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData);
        $result = $result && $this->verifyGetLocalMessagesArrayHasStringsForValues($xData);

        foreach ($throwableClassStrings as $classString) {
            $message = $xData->getXMessageTemplate($classString);
            $messageVariables = $this->parseVariableNamesFromMessage($message);

            $result = $result && $this->verifyExceptionConstructorIsCorrect($classString);
            $result = $result && $this->verifyExceptionAndMessageParametersMatch($classString, $messageVariables);
            $result = $result && $this->verifyExceptionCanBeInstantiated($classString);
            $result = $result && $this->verifyExceptionExtendsPvcStockException($classString);
        }
        return $result;
    }

    /**
     * verifyXDataKeysMatchClassStringsFromDir
     * @param XDataInterface $xData
     * @param array<string> $throwableClassStrings
     * @return bool
     */
    public function verifyXDataKeysMatchClassStringsFromDir(XDataInterface $xData, array $throwableClassStrings): bool
    {
        $codesArray = $xData->getLocalXCodes();
        $messagesArray = $xData->getXMessageTemplates();
        $keysForCodes = array_keys($codesArray);
        $keysForMessages = array_keys($messagesArray);

        $result = true;

        $keysForCodesThatHaveNoExceptionDefined = array_diff($keysForCodes, $throwableClassStrings);
        if (!empty($keysForCodesThatHaveNoExceptionDefined)) {
            foreach ($keysForCodesThatHaveNoExceptionDefined as $key) {
                echo sprintf("codes key %s has no corresponding exception defined.\n", $key);
            }
            $result = false;
        }


        $keysForMessagesThatHaveNoExceptionDefined = array_diff($keysForMessages, $throwableClassStrings);
        if (!empty($keysForMessagesThatHaveNoExceptionDefined)) {
            foreach ($keysForMessagesThatHaveNoExceptionDefined as $key) {
                echo sprintf("messages key %s has no corresponding exception defined.\n", $key);
            }
            $result = false;
        }

        $exceptionsWithNoCodeDefined = array_diff($throwableClassStrings, $keysForCodes);
        if (!empty($exceptionsWithNoCodeDefined)) {
            foreach ($exceptionsWithNoCodeDefined as $key) {
                echo sprintf("exception %s has no corresponding code defined.\n", $key);
            }
            $result = false;
        }

        $exceptionsWithNoMessageDefined = array_diff($throwableClassStrings, $keysForMessages);
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
    public function getThrowableClassStrings(XDataInterface $xData): array
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

        /** @var array<int, class-string> $classStrings */
        $classStrings = [];

        foreach ($files as $file) {
            /**
             * get the class string by parsing the file
             */
            $fileContents = file_get_contents($dir . DIRECTORY_SEPARATOR . $file) ?: '';
            $classString = Exception::getClassStringFromFileContents($fileContents);

            /**
             * validate the class string:  must be reflectable (i.e. an object) and Throwable.  In order to provide
             * better diagnostic information, we are not checking to see if the exception extends
             * \pvc\err\stock\Exception at this point.
             */
            if ($classString) {
                try {
                    $reflected = new ReflectionClass($classString);
                    if ($reflected->implementsInterface(Throwable::class)) {
                        $classStrings[] = $classString;
                    }
                } catch (ReflectionException $e) {
                    /** either reflection failed or it was not Throwable */
                }
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
            echo "not all exception codes are unique.\n";
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
        /** @noinspection PhpPointlessBooleanExpressionInConditionInspection */
        if (false == (array_reduce($codesArray, $callback, $initialValue))) {
            echo "not all exception codes are integers.\n";
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
        /** @noinspection PhpPointlessBooleanExpressionInConditionInspection */
        if (false == (array_reduce($messagesArray, $callback, $initialValue))) {
            echo "not all exception messages are strings.\n";
            $result = false;
        }
        return $result;
    }

    /**
     * verifyExceptionConstructorIsCorrect
     * @param class-string<Throwable> $classString
     * @return bool
     * @throws ReflectionException
     */
    public function verifyExceptionConstructorIsCorrect(string $classString): bool
    {
        $reflected = new ReflectionClass($classString);
        if (!$this->exceptionHasExplicitConstructor($reflected)) {
            /**
             * It is OK if there is no constructor for the exception as long as the message has no variables in it.
             * The rest of the tests depend on there being a constructor defined, so return true now.
             */
            return true;
        }

        $result = true;
        $constructor = $reflected->getConstructor();
        $reflectionParams = $constructor ? $constructor->getParameters() : [];
        $countOfParams = count($reflectionParams);

        /**
         * all exceptions with a constructor must have at least one parameter.  Return false because subsequent tests
         * depend on there being at least one parameter
         */
        if ($countOfParams == 0) {
            echo sprintf("%s has no parameters and must have at least a \$prev parameter.\n", $classString);
            return false;
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
        return $result;
    }

    /**
     * verifyExceptionAndMessageParametersMatch
     * @param class-string<Throwable> $classString
     * @param array<string> $messageParameters
     * @return bool
     * @throws ReflectionException
     */
    public function verifyExceptionAndMessageParametersMatch(
        string $classString,
        array $messageParameters
    ): bool {
        $reflected = new ReflectionClass($classString);
        /**
         * if the exception has no constructor but there are variables in the message then return false. If the
         * exception has non constructor and there are no variables in the message, then return true.
         */
        if (!$this->exceptionHasExplicitConstructor($reflected)) {
            if (!empty($messageParameters)) {
                echo sprintf(
                    "%s has no constructor but has message variables in its exception data file.\n",
                    $classString
                );
                return false;
            }
            return true;
        }

        /**
         * We know the exception has a constructor, so now we can get the parameters into an array, remove the $prev
         * parameter because it does not appear in the message and then compare parameter names and message variable
         * names.
         */
        $constructor = $reflected->getConstructor();
        $reflectionParams = $constructor ? $constructor->getParameters() : [];

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

        sort($paramNames);
        sort($messageParameters);

        if ($paramNames != $messageParameters) {
            $format = "The parameters of %s do not match the variable names in the corresponding message.\n";
            echo sprintf($format, $classString);
            return false;
        }

        return true;
    }

    /**
     * exceptionHasExplicitConstructor
     * @param ReflectionClass<Throwable> $reflected
     * @return bool
     * returns true if there is a __construct method explicit defined in the class
     * returns false if this is a child class and the constructor is inherited
     * returns false if this is a child class and there is no constructor anywhere up the inheritance chain
     */
    public function exceptionHasExplicitConstructor(ReflectionClass $reflected): bool
    {
        /**
         * The getConstructor method returns null if there is no constructor for the class at all.  This can occur in
         * either of two ways: 1) this class has no parent and no __construct method.  2) This class has a parent
         * AND there is no constructor anywhere up the inheritance chain.
         *
         * Because all exceptions in the exception library SHOULD extend \pvc\err\stock\Exception, the getConstructor
         * method should never return null in this context.  But, it is a public method so to be safe.....
         */
        $isChildClass = (bool)$reflected->getParentClass();

        $parent = $reflected->getParentClass();

        $parentConstructor = ($parent ? $parent->getConstructor() : false);

        $myConstructor = $reflected->getConstructor();

        /**
         * if $myConstructor is not null and the constructor of the parent class is the same as the constructor of this
         * class, then this class inherited the constructor, e.g. we know that there is no explicit constructor
         * in $myException
         */
        if ($isChildClass) {
            return !($myConstructor && ($myConstructor == $parentConstructor));
        }
        /**
         * otherwise, this is a standalone class
         */
        return (bool)$myConstructor;
    }

    /**
     * verifyExceptionCanBeInstantiated
     * @param class-string $classString
     * @return bool
     * @throws ReflectionException
     */
    public function verifyExceptionCanBeInstantiated(string $classString): bool
    {
        $reflected = new ReflectionClass($classString);
        $constructor = $reflected->getConstructor();
        $reflectionParams = $constructor ? $constructor->getParameters() : [];

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
        return ($instance instanceof $classString);
    }

    /**
     * verifyExceptionExtendsPvcStockException
     * @param class-string $classString
     * @return bool
     * @throws ReflectionException
     */
    public function verifyExceptionExtendsPvcStockException(string $classString): bool
    {
        $reflected = new ReflectionClass($classString);
        return $reflected->isSubclassOf(Exception::class);
    }

    /**
     * parseVariableNamesFromMessage
     * @param string $message
     * @return array<string>
     */
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
        /** @noinspection PhpPointlessBooleanExpressionInConditionInspection */
        if ((false == ($reflectionType instanceof ReflectionNamedType))) {
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
     * @param string $paramType
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
    public function createDummyParamValueBasedOnType(string $paramType): bool|int|string
    {
        return match ($paramType) {
            'string' => 'foo',
            'integer', 'int' => 5,
            'bool' => true,
            default => '{' . $paramType . '}',
        };
    }

    /**
     * getReflectionNamedType
     * @param ReflectionType|null $paramType
     * @return string
     * returns a base datatype suitable for creating a dummy parameter value
     */
    public function getReflectionTypeName(?ReflectionType $paramType): string
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
         * we know it is an intersection or union type, pick the first element in the array
         */
        /** @var \ReflectionUnionType|\ReflectionIntersectionType $paramType */
        $typesArray = $paramType->getTypes();
        $type = $typesArray[0];
        return $type->getName();
    }
}
