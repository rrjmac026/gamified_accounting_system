<?php
namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Laravel\Fortify\Rules\Password as PasswordRule;

class ResetUserPassword implements ResetsUserPasswords 
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array<string, string>  $input
     * @return void
     */
    public function reset($user, array $input): void
    {
        Validator::make($input, [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
            'remember_token' => null,
        ])->save();
    }
}

