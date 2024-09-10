<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */
declare(strict_types=1);

namespace pvc\err\stock;

/**
 * Class ErrorException
 */
class ErrorException extends \ErrorException
{
    /**
     * pvc does not make use of the code parameter when creating ErrorExceptions, it is always set to zero.
     */
    public function __construct()
    {
        $errinfo = error_get_last();
        parent::__construct($errinfo['message'], 0, $errinfo['type'], $errinfo['file'], $errinfo['line'],);
    }
}
