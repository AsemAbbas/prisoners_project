<?php

namespace App\Enums;

enum UserStatus: string
{
    case one = 'مسؤول';
    case two = 'مراجع منطقة';
    case four = 'محرر أخبار';
}
