<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version 1.0
 */

namespace pvcTests\err;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

/**
 * Class NodeVisitorClassName
 * @package tests\php_parser
 */
class PhpParserNodeVisitorClassName extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    protected string $className;

    /**
     * enterNode inspects the current node and if it is an instance of Class_, returns the class string/class name
     * @param Node $node
     * @return int|null
     */
    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Class_) {
            /**
             * if there's namespacing in the file, get the full namespaced class name (i.e. class string).  If there
             * is no namespacing, then the namespacedName property will be null.
             */
            $classString = $node->namespacedName->toString();

            /**
             * if class string was not populated, then use the property value
             */
            if (empty($classString)) {
                $this->className = $node->name;
            }
            /**
             * otherwise use the name of the class (no namespace exists)
             */
            else {
                $this->className = $classString;
            }

            /**
             * return value STOP_TRAVERSAL stops the node traverser from going any further
             */
            return NodeTraverser::STOP_TRAVERSAL;
        }
        /**
         * returning null will have the traverser keep traversing nodes in the AST.
         */
        return null;
    }

    public function getClassname(): string
    {
        return $this->className;
    }
}
