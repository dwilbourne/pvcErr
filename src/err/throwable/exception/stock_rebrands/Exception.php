<?php declare(strict_types = 1);
namespace pvc\err\throwable\exception\stock_rebrands;

use pvc\err\throwable\ErrorExceptionConstants as ec;
use pvc\msg\ErrorExceptionMsg;
use pvc\err\throwable\Throwable;

/**
 *
 * To see the stock exception and error hierarchy that your system has defined,
 * use the ThrowablesDocumenter object and compare that list with the exceptions and errors contained in
 * the stock pvc framework.
 *
 */
class Exception extends \Exception implements Throwable
{
    protected ErrorExceptionMsg $msg;

    public function __construct(ErrorExceptionMsg $msg = null, int $code = 0, \Throwable $previous = null)
    {
        if (is_null($msg)) {
            $msgText = 'An unspecified exception has occurred.';
            $vars = [];
            $msg = new ErrorExceptionMsg($vars, $msgText);
        }
        $this->setMsg($msg);
        if ($code == 0) {
            $code = ec::EXCEPTION;
        }
        parent::__construct('', $code, $previous);
    }

    public function setMsg(ErrorExceptionMsg $msg): void
    {
        $this->msg = $msg;
    }

    public function getMsg(): ErrorExceptionMsg
    {
        return $this->msg;
    }
}
