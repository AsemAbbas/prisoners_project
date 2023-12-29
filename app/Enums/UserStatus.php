<?php

namespace App\Enums;

enum UserStatus: string
{
    case one = 'مسؤول';
    case two = 'مدخل بيانات';
    case three = 'مراجع بيانات';
}
