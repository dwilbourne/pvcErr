<?php

declare(strict_types=1);


namespace pvc\err\metadata;

enum Packages: int
{
    case PVC_ERR = 10;
    case PVC_FRMTR = 11;
    case PVC_PARSER = 12;
}