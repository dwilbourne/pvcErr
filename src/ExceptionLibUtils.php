<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */
declare (strict_types=1);

namespace pvc\err;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use pvc\interfaces\err\XDataInterface;
use pvc\err\PhpParserNodeVisitorClassName;
use ReflectionClass;
use Throwable;

/**
 * Class ExceptionLibUtils
 *
 * This class contains a couple of static methods that are used in more than one place in the pvcErr package.
 */
class ExceptionLibUtils
{

    /**
     * @function validateExceptionClassString
     * @param class-string $classString
     * @return ?ReflectionClass<Throwable>
     */
    public static function validateExceptionClassString(string $classString): ?ReflectionClass
    {
        try {
            /**
             * this will throw an exception if the string is not reflectable.
             * @var ReflectionClass<Throwable>
             */
            $reflection = new ReflectionClass($classString);
            /**
             * return null if the class is not throwable
             */
            return $reflection->implementsInterface(Throwable::class) ? $reflection : null;

        } catch (\ReflectionException $e) {
            return null;
        }

    }

    /**
     * This method uses nikic's PhpParser to parse each file in the exception library (directory) and
     * extract the class string or, if the class is not namespaced, the class name.
     *
     * @function getClassStringFromFile
     * @param string $filename
     * @return string|false
     */
    public static function getClassStringFromFile(string $filename): string|false
    {
        $code = file_get_contents($filename);
        if ($code !== false) {
            $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
            try {
                /**
                 * $nodes array is the Abstract Syntax Tree (AST)
                 */
                $nodes = $parser->parse($code);
                /**
                 * PhpParser object which traverses the AST
                 */
                $traverser = new NodeTraverser();

                /**
                 * PhpParser object NameResolver populates $node->namespacedName if it can
                 */
                $nameResolver = new NameResolver();

                /**
                 * despite the name, this object is part of pvcErr and its main job is to stop the traversal as soon
                 * as the class string has been found in the file
                 */
                $classVisitor = new PhpParserNodeVisitorClassName();

                /**
                 * add the two visitor objects, traverse the tree until the class string is found and return it.
                 */
                $traverser->addVisitor($nameResolver);
                $traverser->addVisitor($classVisitor);
                /**
                 * minor typing error that phpostan complains about inside PhpParser.  Indicates type for $nodes is
                 * actually Nodes\Stmt....
                 * @phpstan-ignore-next-line
                 */
                $traverser->traverse($nodes);
                return $classVisitor->getClassname();

            } catch (Throwable $error) {
                /** skip files which do not parse */
            }
        }
        /**
         * file_get_contents returned false, which is very bad, as in there was a disk read error of some kind
         */
        return false;
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
    public static function discoverXDataFromClassString(string $classString): ?XDataInterface
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
            $className = ExceptionLibUtils::getClassStringFromFile($dir . DIRECTORY_SEPARATOR . $file);
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

}