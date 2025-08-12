<?php

namespace App\Enums;

enum StatusEventEnum: string
{
    case Inactive = 'inactive';
    case Upcoming = 'upcoming';
    case Ongoing = 'ongoing';
    case Finished = 'finished';  
    case Cancelled = 'cancelled';
}
