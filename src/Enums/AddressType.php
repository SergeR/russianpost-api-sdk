<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Enums;

enum AddressType: string
{
    case DEFAULT = 'DEFAULT';
    case PO_BOX = 'PO_BOX';
    case DEMAND = 'DEMAND';
    case UNIT = 'UNIT';
}
