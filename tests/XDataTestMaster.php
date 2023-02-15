<?php

declare(strict_types=1);

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

namespace pvcTests\err;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use pvc\err\PhpParserNodeVisitorClassName;
use pvc\interfaces\err\XDataInterface;
use ReflectionClass;
use ReflectionException;
use Throwable;

class XDataTestMaster extends TestCase
{
    /**
     * @var array<int, class-string>
     */
    private array $exceptionClassStrings;

    /**
     * @var XDataInterface
     */
    private XDataInterface $xData;


    /**
     * verifylibrary
     * @param class-string $xDataClassString
     */
    public function verifylibrary(string $xDataClassString): void
    {
        /**
         * doing this in two steps so we can typehint object, otherwise phpstan complains
         * @var XDataInterface $object
         */
        $object = new $xDataClassString();
        $this->xData = $object;
        $this->exceptionClassStrings = $this->getExceptionClassStringsFromXDataDir();
        $this->verifyGetLocalCodesKeysMatchClassStringsFromDir();
        $this->verifyGetLocalMessagesKeysMatchClassStringsFromDir();
        $this->verifyGetLocalCodesArrayHasUniqueIntegerValues();
        $this->verifyGetLocalMessagesArrayHasStringsForValues();
    }

    /**
     * getExceptionClassStringsFromDir
     * @return array<int, class-string>
     */
    protected function getExceptionClassStringsFromXDataDir(): array
    {
        /**
         * reflect XData and get the directory portion of the file name
         */
        $reflectedXData = new ReflectionClass($this->xData);
        $filePath = $reflectedXData->getFileName() ?: '';
        $dir = pathinfo($filePath, PATHINFO_DIRNAME);

        /**
         * put all the files from the directory into an array, removing any directory entries
         */
        if (false === ($files = scandir($dir))) {
            return [];
        }
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

    public function verifyGetLocalCodesKeysMatchClassStringsFromDir(): void
    {
        $keys = array_keys($this->xData->getLocalXCodes());
        self::assertEqualsCanonicalizing($keys, $this->exceptionClassStrings);
    }

    public function verifyGetLocalMessagesKeysMatchClassStringsFromDir(): void
    {
        $keys = array_keys($this->xData->getXMessageTemplates());
        self::assertEqualsCanonicalizing($keys, $this->exceptionClassStrings);
    }

    public function verifyGetLocalCodesArrayHasUniqueIntegerValues(): void
    {
        $codes = $this->xData->getLocalXCodes();

        /**
         * verify that the count of unique codes equals the total count of codes
         */
        self::assertEquals(count(array_unique($codes)), count($codes));

        /**
         * verify $codes is all integers.
         * if $codes is empty, $initialValue will be false and then array_reduce returns false.
         */
        $initialValue = !empty($codes);
        $callback = function ($carry, $x) {
            return ($carry && is_int($x));
        };
        self::assertTrue(array_reduce($codes, $callback, $initialValue));
    }

    public function verifyGetLocalMessagesArrayHasStringsForValues(): void
    {
        /**
         * verify $messages is all strings
         * if $codes is empty, $initialValue will be false and then array_reduce returns false.
         */
        $messages = $this->xData->getXMessageTemplates();
        $initialValue = !empty($messages);
        $callback = function ($carry, $x) {
            return ($carry && is_string($x));
        };
        self::assertTrue(array_reduce($messages, $callback, $initialValue));
    }

    /**
     * this method is borrowed from the pvc\err\pvc\Exception class.  Originally, I even
     * had a separate utilities class which provided the code that could be shared.  But in the interests of
     * keeping the publicly available methods as few as possible, I chose to duplicate the code here....
     */

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
        $code = file_get_contents($filename);
        if ($code === false) {
            return '';
        }

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
}
