<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PostStatus extends Enum
{
    const Active = 'Active';
    const Deleted = 'Deleted';
}