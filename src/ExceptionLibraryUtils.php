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
use pvcTests\err\PhpParserNodeVisitorClassName;
use ReflectionClass;
use Throwable;

/**
 * Class ExceptionLibraryDataLocator
 */
class ExceptionLibraryUtils
{

    /**
     * validateExceptionClassString
     * @param mixed $classString
     * @return ?ReflectionClass
     */
    public static function validateExceptionClassString(mixed $classString): ?ReflectionClass
    {
        try {
            /**
             * this will throw an exception if the string is not reflectable.
             */
            $reflection = new ReflectionClass($classString);
            /**
             * phpstan get a little confused here.  It wants you to properly typehint $classString as a class-string
             * and then when you do that, complains that the catch clause is dead because a class string can always
             * be reflected......
             *
             * @phpstan-ignore-next-line
             */
        } catch (\ReflectionException $e) {
            return null;
        }

        /**
         * return false if the class is not throwable
         */
        if (!$reflection->implementsInterface(Throwable::class)) {
            return null;
        }
        return $reflection;
    }

    /**
     * getClassStringFromFile uses nikic's PhpParser to parse each file in the exception library (directory) and
     * extract the class string or, if the class is not namespaced, the class name.
     *
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

}