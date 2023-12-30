<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version 1.0
 */

namespace pvcTests\err;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;
use PHPUnit\Framework\TestCase;
use pvc\err\PhpParserNodeVisitorClassName;

class PhpParserNodeVisitorClassNameTest extends TestCase
{
    /**
     * @var Parser
     */
    protected Parser $parser;
    /**
     * @var string
     */
    protected string $fixtureDir;

    /**
     * @function setUp
     */
    public function setUp(): void
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtureForXDataTests';
    }

    /**
     * @function dataProvider
     * @return array<int, array<int, string>>
     */
    public function dataProvider(): array
    {
        return [
            ['SampleException.php', "pvcTests\\err\\fixtureForXDataTests\\SampleException"],
            ['ClassWithNoNamespace.php', 'ClassWithNoNamespace'],
            ['NotAClass.php', ''],
        ];
    }

    /**
     * @function testNodeVisitorClassName
     * @param string $fileName
     * @param string $expectedResult
     * @covers       \pvc\err\PhpParserNodeVisitorClassName::enterNode
     * @covers       \pvc\err\PhpParserNodeVisitorClassName::getClassname
     * @covers       \pvc\err\PhpParserNodeVisitorClassName::getNamespaceName
     * @dataProvider dataProvider
     */
    public function testNodeVisitorClassName(string $fileName, string $expectedResult): void
    {
        $filePath = $this->fixtureDir . DIRECTORY_SEPARATOR . $fileName;
        $fileContents = file_get_contents($filePath) ?: '';
        /** @var array<Node> $nodes */
        $nodes = $this->parser->parse($fileContents);
        $traverser = new NodeTraverser();
        $classVisitor = new PhpParserNodeVisitorClassName();
        $traverser->addVisitor($classVisitor);
        $traverser->traverse($nodes);
        if ($classString = $classVisitor->getClassname()) {
            if ($namespaceName = $classVisitor->getNamespaceName()) {
                $classString = $namespaceName . '\\' . $classString;
            }
        }
        self::assertEquals($expectedResult, $classString);
    }
}
