<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class AuthenticateService
{
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function getAdminUser()
    {
        return User::where('role_id', Role::ADMIN_ROLE_ID)->first();
    }

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getUserByToken($token)
    {
        return User::where('remember_token', $token)->first();
    }

    public function sendResetPasswordEmail($email)
    {
        $user = $this->getUserByEmail($email);
        if (!$user) {
            throw new UnauthorizedException('Account not found, please try again.');
        }
        $user->remember_token = md5($email).time().Str::random(5);
        $user->save();
        $name = $user->name;
        $reset_password_link = route('reset_password_link', ['token' => $user->remember_token]);
        $result = [
            'name' => $name,
            'reset_password_link' => $reset_password_link,
        ];
        $this->emailService->sendEmail('emails.send-user-password-reset', $result, $user->email, 'Reset Your Password on Service');
    }

    /**
     * @param user The user object
     * @param new_password string
     */
    public function changeUserPassword($user, $new_password)
    {
        $user->password = Hash::make($new_password);
        $user->save();
    }
}
