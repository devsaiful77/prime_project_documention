<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SpecialCharacterFilter implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (preg_match('/[^0-9a-zA-Z@._,\-() ]/', $value)) {
            $fail('Invalid character detected! Only Text, Number, @ . _ , - ( ) and spaces are allowed.');
            return;
        }

        $jsKeywordPattern = '/\b(alert|prompt|confirm|script|on\w+|eval|javascript|function|constructor|fetch|settimeout|setinterval|new|document|window|location)\b/i';

        if (preg_match($jsKeywordPattern, $value)) {
            $fail('JavaScript keywords are not allowed!');
        }
    }
}

