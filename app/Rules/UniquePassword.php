<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UniquePassword implements ValidationRule
{
    protected $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Get all users except the current user (if updating)
        $users = User::when($this->userId, function ($query) {
            return $query->where('user_id', '!=', $this->userId);
        })->get();

        // Check if any user has the same password
        foreach ($users as $user) {
            if (Hash::check($value, $user->password)) {
                $fail('This password is already being used by another user. Please choose a different password.');
                return;
            }
        }
    }
}
