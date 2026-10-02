<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('ATMS-Verifikasi Akun')
                ->line('Klik tombol berikut untuk melakukan verifikasi alamat email anda.')
                ->action('Verifikasi Alamat Email Anda', $url)
                ->line('Jika anda sudah melakukan verifikasi alamat email anda atau permintaan ini bukan dari anda, abaikan email ini.');
        });
    }

    // public function boot()
    // {
    //     $this->registerPolicies();
    //     // VerifyEmail::toMailUsing(function ($notifiable, $url) {
    //     //     return (new MailMessage)
    //     //         ->subject('Verify Email Address')
    //     //         ->line('Click the button below to verify your email address.')
    //     //         ->action('Verify Email Address', $url);
    //     // });
    //     //
    // }
}
