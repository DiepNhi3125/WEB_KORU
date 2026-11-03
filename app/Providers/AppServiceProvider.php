<?php

namespace App\Providers;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
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
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
    return (new MailMessage)
        ->subject('Xác thực tài khoản KORU')
        ->greeting('Xin chào!')
        ->line('Vui lòng xác thực email để kích hoạt tài khoản KORU.')
        ->action('Xác thực email', $url)
        ->line('Nếu bạn không đăng ký tài khoản, vui lòng bỏ qua email này.')
        ->salutation('Trân trọng, KORU');
});
    }
}
