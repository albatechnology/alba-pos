<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatus extends Enum
{
    const QUOTATION = 1;
    const SHIPMENT  = 2;
    const DELIVERING = 3;
    const ARRIVED    = 4;
    const DONE    = 5;
    const CANCELLED = 6;
    const RETURNED  = 7;

    public static function getDescription($value): string
    {
        return match ($value) {
            default => self::getKey($value),
        };
    }
}
