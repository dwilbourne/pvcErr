<?php declare(strict_types = 1);
namespace pvc\err;

use pvc\err\stock\ExceptionConstants;
use pvc\err\throwable\Throwable;
use pvc\msg\MsgInterface;

/**
 *
 * To see the stock exception and error hierarchy that your system has defined,
 * use the ThrowablesDocumenter object and compare that list with the exceptions and errors contained in
 * the stock pvc framework.
 *
 */
class Exception extends \Exception implements Throwable
{
    protected MsgInterface $msg;
    protected int $stockExceptionCodeDefault = 0;

    public function __construct(MsgInterface $msg, \Throwable $previous = null)
    {
        $this->setMsg($msg);
        parent::__construct('', $this->stockExceptionCodeDefault, $previous);
        $code = ExceptionConstants::GENERIC_EXCEPTION_CODE;
    }

    public function setMsg(Msginterface $msg): void
    {
        $this->msg = $msg;
    }

    public function getMsg(): MsgInterface
    {
        return $this->msg;
    }
}
