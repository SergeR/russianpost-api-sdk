<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Enums;

enum ReturnAddressType: string
{
    case POSTOFFICE_ADDRESS = 'POSTOFFICE_ADDRESS';
    case SPECIFIED_ADDRESS = 'SPECIFIED_ADDRESS';
}
