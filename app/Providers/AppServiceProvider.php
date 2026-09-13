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
        // Auto-heal stale Vite hot file if Vite dev server is offline
        if (app()->isLocal() && file_exists(public_path('hot'))) {
            $hotUrl = trim((string) @file_get_contents(public_path('hot')));
            if ($hotUrl) {
                $parts = parse_url($hotUrl);
                $host  = trim($parts['host'] ?? '127.0.0.1', '[]');
                $port  = (int) ($parts['port'] ?? 5173);
                $fp    = @fsockopen($host, $port, $errno, $errstr, 0.15);
                if (!$fp) {
                    @unlink(public_path('hot'));
                } else {
                    fclose($fp);
                }
            }
        }

        \Illuminate\Pagination\Paginator::useTailwind();

        // Enforce short timeout for SMTP so requests/actions are never stalled
        config(['mail.mailers.smtp.timeout' => 5]);

        // Register Model Observers
        \App\Models\Project::observe(\App\Observers\ProjectObserver::class);

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
