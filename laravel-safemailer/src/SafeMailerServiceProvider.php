<?php

namespace SafeMailer;

use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;
use SafeMailer\Transport\SafeMailerTransport;

class SafeMailerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->afterResolving('mail.manager', function (MailManager $manager) {
            $manager->extend('safemailer', function () {
                return new SafeMailerTransport(
                    config('safemailer.api_key')
                );
            });
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'safemailer');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/safemailer.php', 'safemailer'
        );
    }
} 