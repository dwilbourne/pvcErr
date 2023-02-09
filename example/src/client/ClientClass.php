<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */
declare (strict_types=1);

namespace pvcExamples\err\src\client;


use pvc\interfaces\err\XFactoryInterface;
use pvcExamples\err\src\client\err\MyException;

/**
 * Class ClientClass
 */
class ClientClass
{
    /**
     * @var XFactoryInterface Make sure you typehint to interfaces, not to concrete classes.  This attribute gets
     * populated in the dependency injection container whenever this class is instantiated.
     */
    protected XFactoryInterface $xFactory;

    public function throwExceptionExample(): void
    {
        /**
         * parameter(s) to the exception messages are always packaged in an array
         */
        $exceptionMessageParams = [4];

        /**
         * if we were in the middle of a try / catch block then $prev is the instance of the previous exception
         */
        $prev = null;

        /** now throw the exception */
        throw $this->xFactory->createException(MyException::class, $exceptionMessageParams, $prev);
    }
}