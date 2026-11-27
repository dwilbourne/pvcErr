<?php

declare(strict_types=1);


namespace pvc\err\metadata;

enum ExceptionType: int
{
    case  Authentication = 1;
    case  Authorization = 2;
    case  NotFound = 3;
    case  Conflict = 4;
    case  RateLimit = 5;
    case  ExternalIo = 6;
    case  Persistence = 7;
    case  System = 8;
}