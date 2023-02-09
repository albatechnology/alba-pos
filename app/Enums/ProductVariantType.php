<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SINGLE()
 * @method static static MULTIPLE()
 */
final class ProductVariantType extends Enum
{
    const SINGLE =   0;
    const MULTIPLE =   1;
}
