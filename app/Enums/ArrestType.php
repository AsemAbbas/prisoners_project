<?php

namespace App\Enums;

enum ArrestType: string
{
    case one = 'محكوم';
    case two = 'إداري';
    case three = 'موقوف';
}
