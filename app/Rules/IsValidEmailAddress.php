<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsValidEmailAddress implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    public function passes($attribute, $value): bool
    {
        // return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
        if (! is_string($value)) {
            throw new \Error('The value must be a string.');
        }

        return preg_match_all('/^\S+@\S+\.\S+$/', $value) === 1;
    }
}
