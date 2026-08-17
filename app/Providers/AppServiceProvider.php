<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Azure\Provider as AzureProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

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
        // Register Microsoft Azure Socialite Provider
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('azure', AzureProvider::class);
        });

        // Share appName dynamically from DB settings to all blade views
        if (!app()->runningInConsole()) {
            try {
                $appName = \App\Models\SystemSetting::where('key', 'app_name')->value('value') ?? 'GS NexusPM';
                view()->share('appName', $appName);
            } catch (\Exception $e) {
                view()->share('appName', 'GS NexusPM');
            }

            // Dynamically override mail config from database SMTP settings
            try {
                $smtpHost       = \App\Models\SystemSetting::where('key', 'smtp_host')->value('value');
                $smtpPort       = \App\Models\SystemSetting::where('key', 'smtp_port')->value('value');
                $smtpUser       = \App\Models\SystemSetting::where('key', 'smtp_user')->value('value');
                $smtpPass       = \App\Models\SystemSetting::where('key', 'smtp_pass')->value('value');
                $smtpEncryption = \App\Models\SystemSetting::where('key', 'smtp_encryption')->value('value');
                $fromAddress    = \App\Models\SystemSetting::where('key', 'mail_from_address')->value('value');
                $fromName       = \App\Models\SystemSetting::where('key', 'mail_from_name')->value('value');

                // Placeholder/invalid hosts to skip (use .env instead)
                $invalidHosts = ['smtp.mailtrap.io', '127.0.0.1', 'localhost', 'mailhog', ''];

                // Only override if DB has a real, non-placeholder SMTP host + username
                if ($smtpHost && $smtpUser && !in_array(strtolower($smtpHost), $invalidHosts)) {
                    $encryption = strtolower($smtpEncryption ?? 'tls');
                    $scheme     = ($encryption === 'ssl') ? 'smtps' : null;

                    config([
                        'mail.mailers.smtp.host'       => $smtpHost,
                        'mail.mailers.smtp.port'       => (int) ($smtpPort ?? 587),
                        'mail.mailers.smtp.username'   => $smtpUser,
                        'mail.mailers.smtp.password'   => $smtpPass,
                        'mail.mailers.smtp.scheme'     => $scheme,
                        'mail.mailers.smtp.encryption' => ($encryption === 'ssl') ? null : $encryption,
                        'mail.mailers.smtp.timeout'    => 10,
                        'mail.from.address'            => $fromAddress ?? config('mail.from.address'),
                        'mail.from.name'               => $fromName    ?? config('mail.from.name'),
                    ]);
                } else {
                    // Use .env settings but enforce a short timeout
                    config(['mail.mailers.smtp.timeout' => 10]);
                }
            } catch (\Exception $e) {
                // DB not ready – fall back to .env settings silently
            }
        } else {
            view()->share('appName', 'GS NexusPM');
        }
    }
}
