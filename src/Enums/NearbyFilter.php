<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Enums;

enum NearbyFilter: string
{
    case ALL = 'ALL';
    case ROUND_THE_CLOCK = 'ROUND_THE_CLOCK';
    case CURRENTLY_WORKING = 'CURRENTLY_WORKING';
    case WORK_ON_WEEKENDS = 'WORK_ON_WEEKENDS';
}
