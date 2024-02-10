<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PalestineIdValidationRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        // Remove non-numeric characters
        $value = preg_replace('/[^\d]/', '', $value);

        // Check if the ID has 9 digits
        if (strlen($value) !== 9 || $value == "000000000") {
            return false;
        }

        // Extract the actual value of the 9th digit
        $expectedDigit = (int)$value[8];

        $multipliers = [1, 2, 1, 2, 1, 2, 1, 2, 1];
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $digit = (int)$value[$i] * $multipliers[$i];

            // If the result is a two-digit number, add the digits separately
            $sum += ($digit > 9) ? $digit - 9 : $digit;
        }

        // Check if the actual value of the 9th digit matches the calculated sum
        return ($sum + $expectedDigit) % 10 === (int)$value[8];
    }

    public function message(): string
    {
        return ':attribute غير صحيح';
    }
}
