<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUPER_ADMIN()
 * @method static static ADMIN()
 * @method static static OptionThree()
 */
final class UserLevelEnum extends Enum
{
    const SUPER_ADMIN = 1; // for Alba users
    const ADMIN = 2; // admin for each company
    const USER = 3; // general user for each company
}
