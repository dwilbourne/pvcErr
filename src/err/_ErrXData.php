<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 * @noinspection PhpCSValidationInspection
 */

declare(strict_types=1);

namespace pvc\err\err;

use pvc\err\err\InvalidXCodePrefixNumberException;
use pvc\err\XDataAbstract;

/**
 * Class _MsgXData
 * @noinspection PhpCSValidationInspection
 */
class _ErrXData extends XDataAbstract
{
    public function getLocalXCodes(): array
    {
        return [
            InvalidXCodePrefixNumberException::class => 1000,
        ];
    }

    public function getXMessageTemplates(): array
    {
        return [
            InvalidXCodePrefixNumberException::class => 'Invalid or duplicate exception code prefix number - ${badPrefix}.',
        ];
    }
}
