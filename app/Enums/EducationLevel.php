<?php

namespace App\Enums;

enum EducationLevel: string
{
    case one = 'توجيهي فما دون';
    case two = 'دبلوم';
    case three = 'بكالوريوس';
    case four = 'ماجستير';
    case five = 'دكتوراة';
}
