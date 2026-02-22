<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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
        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory)->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    config('services.brevo.key')
                )
            );
        });

        /*VerifyEmail::toMailUsing(function (object $notifiable, string $url) {

            $find = url('');
            $url = str_replace($find, businessUrl(), $url);

            return (new MailMessage)
                ->subject('Verify SEVN Account!')
                ->line('Welcome to our platform! Please click the button below to verify your email address and unlock all features.')
                ->action('Activate My Account', $url)
                ->line('Thank you for joining!')
                ->view('mails.account-verify', ['url' => $url]);
        });*/
    }
}
