<?php declare(strict_types = 1);
/**
 * @package: pvc
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @version: 1.0
 */

namespace pvc\err\throwable\exception\pvc_exceptions;

use pvc\msg\ErrorExceptionMsg;

/**
 * Class InvalidAttributeExceptionMsg
 */
class InvalidFilenameMsg extends ErrorExceptionMsg
{
    public function __construct(string $fileName)
    {
        $msgVars = [$fileName];
        $msgText = 'filename (%s) is not valid.';
        parent::__construct($msgVars, $msgText);
    }
}
