<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

use pvc\err\XCodePrefixes;
use pvc\err\XFactory;
use pvc\interfaces\err\XCodePrefixesInterface;
use pvc\interfaces\err\XFactoryInterface;
use pvcExamples\err\src\client\ClientClass;

use function DI\create;
use function DI\get;

return [
    'XCodePrefixesFilePath' => 'I:/www/pvcErr/example/src/XCodePrefixes.json',

    XCodePrefixes::class => create()->constructor(get('XCodePrefixesFilePath')),

    /** XCodePrefixes class is the implementation of the interface */
    XCodePrefixesInterface::class => get(XCodePrefixes::class),

    /**
     * auto wiring means that the container will automatically resolve the constructor parameter for XFactory.  That
     * parameter is typed as XCodePrefixesInterface, which is defined above.
     *
     * XFactory::class => create()->constructor(get(XCodePrefixesInterface::class)),
     */

    /** XFactory class is the implementation of the interface */
    XFactoryInterface::class => get(XFactory::class),

    /** property xFactory has a typehint of XFactoryInterface */
    ClientClass::class => create()->property('xFactory', get(XFactoryInterface::class)),
];
