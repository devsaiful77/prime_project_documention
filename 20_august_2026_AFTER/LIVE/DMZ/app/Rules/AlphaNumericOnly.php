<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlphaNumericOnly implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        // Allow letters, numbers, and spaces
        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $value)) {
            $fail('Only letters, numbers, and spaces are allowed.');
        }
    }
}
