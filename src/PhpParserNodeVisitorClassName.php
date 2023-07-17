<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version 1.0
 */

namespace pvc\err;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

/**
 * PhpParserNodeVisitorClassName gets the class name from an Abstract Syntax Tree and then stops the traversal.
 *
 * The success of the enterNode method setting the className is contingent on
 * running a NameResolver through the node prior to this visitor.  Need to write a decorator for NameResolver that
 * has a method to get the namespacedName property of the node......
 */

class PhpParserNodeVisitorClassName extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    protected string $className;

    protected string $namespaceName;

    /**
     * enterNode inspects the current node and if it is an instance of Class_, returns the class string/class name
     * @param Node $node
     * @return int|null
     */
    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Namespace_) {
            /** phpstan complains if we don't test for null */
            $this->namespaceName = ($node->name ? $node->name->toString() : '');
        }

        if ($node instanceof Class_) {
            /** phpstan complains if we don't test for null */
            $this->className = ($node->name ? $node->name->toString() : '');

            /** return value STOP_TRAVERSAL stops the node traverser from going any further */
            return NodeTraverser::STOP_TRAVERSAL;
        }
        /**
         * returning null will have the traverser keep traversing nodes in the AST.
         */
        return null;
    }

    /**
     * getClassname
     * @return string
     */
    public function getClassname(): string
    {
        return $this->className ?? '';
    }

    /**
     * getNamespaceName
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->namespaceName ?? '';
    }
}
