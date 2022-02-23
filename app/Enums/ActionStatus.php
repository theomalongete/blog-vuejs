<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ActionStatus extends Enum
{
    const Success = 'Success';
    const Error = 'Error';
    const Pending = 'Pending';
    const Created = 'Created';
    const Updated = 'Updated';
    const Deleted = 'Deleted';
    const Suspended = 'Suspended';
    const Unsuspended = 'Unsuspended';
}