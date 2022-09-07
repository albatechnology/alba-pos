<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static INCREASE()
 * @method static static DECREASE()
 */
final class StockTypeEnum extends Enum
{
    const INCREASE =   0;
    const DECREASE =   1;
}
