<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Enums;

enum BatchStatus: string
{
    case CREATED = 'CREATED';
    case SENT_TO_OPERATOR = 'SENT_TO_OPERATOR';
}
