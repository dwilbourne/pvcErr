<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */
declare(strict_types=1);

namespace pvcTests\err\fixturesForXDataTestMaster\exceptionFixtures;

use Throwable;

/**
 * Class ExceptionDoesNotExtendPvcStockException
 */
class ExceptionDoesNotExtendPvcStockException extends \Exception
{
    public function __construct(int $index, Throwable $prev)
    {
        $code = 5;
        $msg = sprintf("Incorrect index %d", $index);
        parent::__construct($msg, $code, $prev);
    }
}