<?php


namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Reset Password ATMS Reg'))
            ->line(Lang::get('Permintaan Reset Password untuk akun anda.'))
            ->action(Lang::get('Reset Password'), url(config('app.url') . '/reset-password/' . $this->token) . '?email=' . urlencode($notifiable->email))
            ->line(Lang::get('Pengaturan Reset Password anda akan kadaluwarsa dalam :count menit.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('Abaikan jika permintaan Reset Password bukan dari anda.'));
    }

    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->subject(Lang::get('Reset Password ATMS Reg'))
    //         ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
    //         ->action(Lang::get('Reset Password'), url(config('app.url') . '/reset-password/' . $this->token) . '?email=' . urlencode($notifiable->email))
    //         ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
    //         ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    // }
}
