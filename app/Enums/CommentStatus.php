<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CommentStatus extends Enum
{
    const Active = 'Active';
    const Deleted = 'Deleted';
}