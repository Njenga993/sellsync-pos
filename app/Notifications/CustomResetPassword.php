<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🔐 Reset Your SellSync Password')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We received a request to reset the password for your SellSync POS account.')
            ->line('Click the button below to set a new password:')
            ->action('Reset Password', url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required — your account is safe.')
            ->salutation('The SellSync Team');
    }
}