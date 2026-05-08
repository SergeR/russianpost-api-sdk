<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Enums;

enum MailType: string
{
    case POSTAL_PARCEL = 'POSTAL_PARCEL';
    case ONLINE_PARCEL = 'ONLINE_PARCEL';
    case ONLINE_COURIER = 'ONLINE_COURIER';
    case EMS = 'EMS';
    case EMS_OPTIMAL = 'EMS_OPTIMAL';
    case EMS_RT = 'EMS_RT';
    case EMS_TENDER = 'EMS_TENDER';
    case LETTER = 'LETTER';
    case LETTER_CLASS_1 = 'LETTER_CLASS_1';
    case SMALL_PACKET = 'SMALL_PACKET';
    case PARCEL_CLASS_1 = 'PARCEL_CLASS_1';
    case ECOM_MARKETPLACE = 'ECOM_MARKETPLACE';
    case BANDEROL = 'BANDEROL';
    case BANDEROL_CLASS_1 = 'BANDEROL_CLASS_1';
    case BANDEROL_KIT = 'BANDEROL_KIT';
}
