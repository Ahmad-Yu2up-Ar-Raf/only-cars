<?php

namespace App\Enums;

enum MerchandiseStatusEnum: string
{
    case Available = 'available';
    case Sold = 'sold';
    case NotAvailable = 'not available';
}
