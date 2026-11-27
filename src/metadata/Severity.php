<?php

declare(strict_types=1);


namespace pvc\err\metadata;

enum Severity: int
{
    case INFO = 1;
    case WARNING = 2;
    case ERROR = 3;
    case FATAL = 4;
}