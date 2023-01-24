<?php

/**
 * @package pvcErr
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\pvc;

use pvc\err\stock\LogicException;

/**
 * InvalidAttributeException should be thrown when someone tries to access an invalid attribute within an object
 *
 * Class PregReplaceFailureException
 * @package pvcErr
 */
class PregReplaceFailureException extends LogicException {}
