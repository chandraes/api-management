<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LivewireFunctionExists implements ValidationRule
{
    protected string $componentClass;

    public function __construct(string $componentClass)
    {
        $this->componentClass = $componentClass;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->componentClass || !class_exists($this->componentClass)) {
            $fail("Komponen Livewire tidak valid.");
            return;
        }

        if (!method_exists($this->componentClass, $value)) {
            $fail("Fungsi '{$value}' tidak ditemukan dalam komponen Livewire.");
        }
    }
}
