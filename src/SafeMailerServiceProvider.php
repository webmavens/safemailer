<?php

namespace SafeMailer;

use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;
use SafeMailer\Transport\SafeMailerTransport;

class SafeMailerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'safemailer');

        // Make config publishable
        $this->publishes([
            __DIR__.'/../config/safemailer.php' => config_path('safemailer.php'),
        ], 'safemailer-config');

        // Register the mailer transport
        $this->app->afterResolving('mail.manager', function (MailManager $manager) {
            $manager->extend('safemailer', function () {
                return new SafeMailerTransport(
                    config('safemailer.api_key')
                );
            });

            // Add the safemailer configuration to the mail config
            config(['mail.mailers.safemailer' => [
                'transport' => 'safemailer',
            ]]);
        });
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/safemailer.php', 'safemailer'
        );

        // Register the facade
        $this->app->singleton('safemailer', function ($app) {
            return new SafeMailer(config('safemailer.api_key'));
        });
    }
} 