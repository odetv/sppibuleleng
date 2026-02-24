<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        
        // 1. KUSTOMISASI EMAIL AKTIVASI (Untuk User yang didaftarkan Admin)
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Aktivasi Akun Baru - SPPI Buleleng')
                ->greeting('Halo!')
                ->line('Akun Anda telah didaftarkan oleh Admin di sistem SPPI Buleleng.')
                ->line('Silakan klik tombol di bawah ini untuk mengatur password dan mengaktifkan akun Anda.')
                ->action('Set Password & Aktivasi', $url)
                ->line('Link aktivasi ini akan kadaluarsa dalam 60 menit.')
                ->line('Jika Anda merasa tidak pernah didaftarkan, silakan abaikan email ini.')
                ->salutation('Hormat kami, Admin SPPI Buleleng');
        });

        // 2. KUSTOMISASI EMAIL VERIFIKASI (Untuk User yang daftar mandiri)
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email - SPPI Buleleng')
                ->greeting('Halo!')
                ->line('Terima kasih telah melakukan registrasi mandiri di sistem SPPI Buleleng.')
                ->line('Klik tombol di bawah ini untuk memverifikasi alamat email Anda agar dapat masuk ke dashboard.')
                ->action('Verifikasi Email Sekarang', $url)
                ->line('Jika Anda tidak merasa melakukan pendaftaran, abaikan saja email ini.')
                ->salutation('Salam hangat, Tim IT SPPI Buleleng');
        });
    }
}