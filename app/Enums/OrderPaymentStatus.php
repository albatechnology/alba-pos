<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderPaymentStatus extends Enum
{
    const NONE         = 1;
    const PARTIAL      = 2;
    const DOWN_PAYMENT = 3;
    const SETTLEMENT   = 4;
    const OVERPAYMENT  = 5;
    const REFUNDED     = 6;

    public static function getDescription($value): string
    {
        return match ($value) {
            default => self::getKey($value),
        };
    }
}
