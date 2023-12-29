<?php

namespace App\Enums;

enum SuggestionStatus: string
{
    case one = 'يحتاج مراجعة';
    case two = 'تم المراجعة';
    case three = 'تم القبول';
}
