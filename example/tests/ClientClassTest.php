<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare (strict_types=1);

namespace pvcExamples\err\tests;

use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use pvcExamples\err\src\client\ClientClass;
use pvcExamples\err\src\client\err\MyException;

class ClientClassTest extends TestCase
{
    protected Container $container;
    protected ClientClass $clientClass;

    public function setUp(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../src/di/DiConfig.php');
        $this->container = $builder->build();
        $this->clientClass = $this->container->get(ClientClass::class);
    }

    /**
     * testClientClassThrowsException
     * @throws \Throwable
     * @covers \pvcExamples\err\src\client\ClientClass::throwExceptionExample
     */
    public function testClientClassThrowsException(): void
    {
        $this->expectException(MyException::class);
        $this->clientClass->throwExceptionExample();
    }
}
