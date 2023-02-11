<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */
declare (strict_types=1);


namespace pvc\err\stock;


use pvc\err\ExceptionCodePrefixes;
use pvc\err\ExceptionLibUtils;
use pvc\interfaces\err\XDataInterface;

/**
 * Class Exception
 */
class Exception extends \Exception {

    protected XDataInterface $xData;

    protected function getXData() : XDataInterface
    {
        return ($this->xData = $this->xData ?? ExceptionLibUtils::discoverXDataFromClassString(get_class()));
    }

    /**
     * @function getMessage
     * @param array $params
     * @return string
     */
    public function createMessage(array $params): string
    {
        /**
         * get the message string from the constants file
         */
        $messageRaw = $this->getXData()->getLocalXMessage(get_class());

        /**
         * convert parameter names into template variables
         */
        foreach ($params as $paramName => $paramValue) {
            $newKey = '${' . $paramName . '}';
            $params[$newKey] = $paramValue;
            unset($params[$paramName]);
        }
        /**
         * replace the template variables with the parameter values and return
         */
        return strtr($messageRaw, $params);
    }

    /**
     * @function getCode
     * @return int
     */
    public function createCode(): int
    {
        $localCode = $this->getXData()->getLocalXCode(get_class());
        $globalPrefix = ExceptionCodePrefixes::PREFIXES[__NAMESPACE__];
        return (int) ($globalPrefix . $localCode);
    }

    public function createParamArray(): array
    {
        $reflected = new \ReflectionClass($this);
        $constructor = $reflected->getConstructor();
        /**
         * put the parameters into an associative array using the argument variables names (which should be the same
         * as the template names in the unformatted message) as the indices.
         *
         * TODO: count the number of templates in the unformatted message and make sure that we lop off the
         * optional previous parameter at the end as necessary
         */
        $params = [];
        foreach ($constructor->getParameters() as $arg) {
            $params[$arg->name] = func_get_arg($arg->getPosition());
        }
        return $params;
    }

}