<?php

declare(strict_types=1);

namespace pvc\err;

use pvc\interfaces\err\XCodeInterface;

/**
 * Class Exception
 */
class Exception extends \Exception
{
    private const PACKAGE_WIDTH = 2;
    private const ERROR_CATEGORY_WIDTH = 2;
    private const SEVERITY_WIDTH = 2;
    private const RETRY_POLICY_WIDTH = 2;

    /**
     * @param  array<string, int|float|string>  $context
     */
    public function __construct(
        public readonly XCodeInterface $xCode,
        array $context = [],
        ?\Throwable $previous = null
    ) {
        /**
         * if $context is empty, there are no parameters and just output the
         * message as is.  Otherwise, run it through the message formatter.
         */
        $message = $context === []
            ? $this->xCode->messageTemplate()
            : XMsgFormatter::format($this->xCode->messageTemplate(), $context);

        parent::__construct($message, $this->metaData(), $previous);
    }

    protected function metaData(): int
    {
        $result = sprintf('%0*d', self::PACKAGE_WIDTH, $this->xCode->package());
        $result .= sprintf(
            '%0*d',
            self::ERROR_CATEGORY_WIDTH,
            $this->xCode->errorCategory()
        );
        $result .= sprintf(
            '%0*d',
            self::SEVERITY_WIDTH,
            $this->xCode->severity()
        );
        $result .= sprintf(
            '%0*d',
            self::RETRY_POLICY_WIDTH,
            $this->xCode->retryPolicy()
        );
        return (int)$result;
    }
}
