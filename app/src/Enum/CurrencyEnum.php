<?php

namespace App\Enum;

enum CurrencyEnum: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case CHF = 'CHF';
    case JPY = 'JPY';
    case CAD = 'CAD';
    case AUD = 'AUD';
    case NZD = 'NZD';
    case SEK = 'SEK';
    case NOK = 'NOK';
    case DKK = 'DKK';

    public const ALLOWED_VALUES = [
        self::USD->value,
        self::EUR->value,
        self::GBP->value,
        self::CHF->value,
        self::JPY->value,
        self::CAD->value,
        self::AUD->value,
        self::NZD->value,
        self::SEK->value,
        self::NOK->value,
        self::DKK->value,
    ];
}
