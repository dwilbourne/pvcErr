<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use pvc\interfaces\err\XDataInterface;
use ReflectionClass;
use ReflectionException;
use Throwable;

class XDataTestMaster extends TestCase
{
    /**
     * verifylibrary
     * @param XDataInterface $xData
     */
    public function verifylibrary(XDataInterface $xData): bool
    {
        $result = $this->verifyKeysMatchClassStringsFromDir($xData);
        $result = $result && $this->verifyGetLocalCodesArrayHasUniqueIntegerValues($xData);
        $result = $result && $this->verifyGetLocalMessagesArrayHasStringsForValues($xData);
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
            $classString = $this->getClassStringFromFile($dir . DIRECTORY_SEPARATOR . $file);

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
     * This method uses nikic's PhpParser to parse each file in the exception library (directory) and
     * extract the class string or, if the class is not namespaced, the class name.
     *
     * This is implemented with two "node
     * visitors".  There's one that comes with the parser package called NameResolver, which obtains the fully
     * namespaced name of the class if it lives in a namespace.  But unfortunately it gets null if the class does not
     * live in a namespace. The PhpParserNodeVisitorClassName object gets the class name (no namespacing) in all
     * cases.  But the other significant feature of the PhpParserNodeVisitorClassName object is that it stops
     * traversal of the tree as soon as the class name is obtained, which should save a few CPU cycles....
     *
     * @function getClassStringFromFileContents
     * @param string $filename
     * @return string
     */
    protected function getClassStringFromFile(string $filename): string
    {
        /**
         * file_get_contents returns false if $filename does not exist so typehint it in this case because arguments
         * here come from a call to scandir.
         */
        /** @var string $code */
        $code = file_get_contents($filename);

        /**
         * create the parser and parse the file.  Result is an array of nodes, which is the AST
         */
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        /** @var Node[] $nodes */
        $nodes = $parser->parse($code);

        /**
         * PhpParser object which traverses the AST.  Add a visitor to the traverser.  This visitor
         * gets namespace and class name strings and stops traversal of the AST after it finds a class name.
         */
        $traverser = new NodeTraverser();
        $classVisitor = new PhpParserNodeVisitorClassName();
        $traverser->addVisitor($classVisitor);
        $traverser->traverse($nodes);

        /**
         * two parts to the class string: the namespace and the class name.  Concatenate the two properly depending
         * on whether there was a class declaration present.
         */
        if ($classString = $classVisitor->getClassname()) {
            if ($namespaceName = $classVisitor->getNamespaceName()) {
                $classString = $namespaceName . '\\' . $classString;
            }
        }
        return $classString;
    }

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
}
