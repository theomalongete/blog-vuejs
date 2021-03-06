<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatus extends Enum
{
    const Active = 'Active';
    const Suspended = 'Suspended';
    const Deleted = 'Deleted';
}