<?php

namespace App\Livewire;

use App\Models\SystemSetting;
use Livewire\Component;

class SettingsManager extends Component
{
    public string $appName = 'GS NexusPM';
    public string $wbsCalculationMethod = 'weighted';
    public bool $enableEmailNotifications = false;

    // SMTP Mail Configuration
    public string $smtpHost = 'smtp.gmail.com';
    public int $smtpPort = 587;
    public string $smtpUser = 'nadumi672@gmail.com';
    public string $smtpPass = 'myhb tzmx aeok nvxj';
    public string $smtpEncryption = 'tls';
    public string $mailFromAddress = 'nadumi672@gmail.com';
    public string $mailFromName = 'GS Project Management';

    // Security & Password Rules
    public bool $enforcePasswordComplexity = true;
    public int $sessionTimeout = 120;

    // Azure AD SSO Integration
    public bool $enableAzureSso = true;
    public string $azureTenantId = 'common';
    public string $azureClientId = '';

    // Test Mail
    public string $testEmailRecipient = 'nadumi672@gmail.com';
    public ?string $testMailStatus = null;
    public ?string $testMailError = null;

    // Toast/Alert state
    public ?string $successToast = null;

    public function mount()
    {
        $this->loadSettings();
        if (empty($this->testEmailRecipient)) {
            $this->testEmailRecipient = 'nadumi672@gmail.com';
        }
    }

    public function loadSettings(): void
    {
        $this->appName = SystemSetting::where('key', 'app_name')->value('value') ?? 'GS NexusPM';
        $this->wbsCalculationMethod = SystemSetting::where('key', 'wbs_calculation_method')->value('value') ?? 'weighted';
        $this->enableEmailNotifications = filter_var(SystemSetting::where('key', 'enable_email_notifications')->value('value') ?? false, FILTER_VALIDATE_BOOLEAN);

        // Mail Server Settings — DB first, then .env fallback
        $this->smtpHost = SystemSetting::where('key', 'smtp_host')->value('value')
            ?? env('MAIL_HOST', 'smtp.gmail.com');
        $this->smtpPort = (int) (SystemSetting::where('key', 'smtp_port')->value('value')
            ?? env('MAIL_PORT', 587));
        $this->smtpUser = SystemSetting::where('key', 'smtp_user')->value('value')
            ?? env('MAIL_USERNAME', 'prathibhajay098@gmail.com');
        $this->smtpPass = SystemSetting::where('key', 'smtp_pass')->value('value')
            ?? env('MAIL_PASSWORD', 'whvcknxyueynxiwk');
        $this->smtpEncryption = SystemSetting::where('key', 'smtp_encryption')->value('value')
            ?? env('MAIL_SCHEME', 'tls');
        $this->mailFromAddress = SystemSetting::where('key', 'mail_from_address')->value('value')
            ?? env('MAIL_FROM_ADDRESS', 'prathibhajay098@gmail.com');
        $this->mailFromName = SystemSetting::where('key', 'mail_from_name')->value('value')
            ?? env('MAIL_FROM_NAME', 'GS Project Management');

        // Security Settings
        $this->enforcePasswordComplexity = filter_var(SystemSetting::where('key', 'enforce_password_complexity')->value('value') ?? true, FILTER_VALIDATE_BOOLEAN);
        $this->sessionTimeout = (int) (SystemSetting::where('key', 'session_timeout')->value('value') ?? 120);

        // Azure SSO Settings
        $this->enableAzureSso = filter_var(SystemSetting::where('key', 'enable_azure_sso')->value('value') ?? true, FILTER_VALIDATE_BOOLEAN);
        $this->azureTenantId = SystemSetting::where('key', 'azure_tenant_id')->value('value') ?? 'common';
        $this->azureClientId = SystemSetting::where('key', 'azure_client_id')->value('value') ?? '';
    }

    public function resetToSaved(): void
    {
        $this->loadSettings();
        $this->testMailStatus = null;
        $this->testMailError = null;
        $this->successToast = 'Reset to saved database settings.';
    }

    public function saveSettings()
    {
        $this->validate([
            'appName' => 'required|string|max:100',
            'wbsCalculationMethod' => 'required|in:weighted,equal',
            'smtpHost' => 'required|string',
            'smtpPort' => 'required|integer|min:1|max:65535',
            'mailFromAddress' => 'required|email',
            'mailFromName' => 'required|string|max:100',
            'sessionTimeout' => 'required|integer|min:5|max:1440',
        ]);

        $settings = [
            'app_name' => [$this->appName, 'general'],
            'wbs_calculation_method' => [$this->wbsCalculationMethod, 'wbs'],
            'enable_email_notifications' => [$this->enableEmailNotifications ? 'true' : 'false', 'notifications'],
            'smtp_host' => [$this->smtpHost, 'notifications'],
            'smtp_port' => [$this->smtpPort, 'notifications'],
            'smtp_user' => [$this->smtpUser, 'notifications'],
            'smtp_pass' => [$this->smtpPass, 'notifications'],
            'smtp_encryption' => [$this->smtpEncryption, 'notifications'],
            'mail_from_address' => [$this->mailFromAddress, 'notifications'],
            'mail_from_name' => [$this->mailFromName, 'notifications'],
            'enforce_password_complexity' => [$this->enforcePasswordComplexity ? 'true' : 'false', 'security'],
            'session_timeout' => [$this->sessionTimeout, 'security'],
            'enable_azure_sso' => [$this->enableAzureSso ? 'true' : 'false', 'security'],
            'azure_tenant_id' => [$this->azureTenantId, 'security'],
            'azure_client_id' => [$this->azureClientId, 'security'],
        ];

        foreach ($settings as $key => $data) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $data[0], 'group' => $data[1]]
            );
        }

        // Record Audit Log
        if (auth()->check()) {
            try {
                \App\Models\ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'updated_system_settings',
                    'module' => 'settings',
                    'record_type' => SystemSetting::class,
                    'record_id' => null,
                    'description' => 'Updated system settings & enterprise configurations',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'new_values' => [
                        'app_name' => $this->appName,
                        'wbs_calculation_method' => $this->wbsCalculationMethod,
                        'enable_email_notifications' => $this->enableEmailNotifications,
                        'smtp_host' => $this->smtpHost,
                        'smtp_port' => $this->smtpPort,
                        'session_timeout' => $this->sessionTimeout,
                        'enforce_password_complexity' => $this->enforcePasswordComplexity,
                        'enable_azure_sso' => $this->enableAzureSso,
                    ],
                ]);
            } catch (\Throwable $e) {
                // Ignore audit log write failure
            }
        }

        // Also update .env file so settings persist across server restarts
        $this->updateEnvMailSettings();

        $this->successToast = 'System settings saved successfully!';
        $this->dispatch('toast', message: 'System settings saved successfully!', type: 'success');
    }

    public function sendTestEmail(): void
    {
        $this->validate([
            'testEmailRecipient' => 'required|email',
        ]);

        $this->testMailStatus = null;
        $this->testMailError = null;

        try {
            // Apply current runtime configs for test dispatch
            $this->updateEnvMailSettings();

            $appName = $this->appName;
            $fromAddr = $this->mailFromAddress;
            $fromName = $this->mailFromName;
            $recipient = $this->testEmailRecipient;
            $host = $this->smtpHost;
            $port = $this->smtpPort;
            $encryption = $this->smtpEncryption;

            \Illuminate\Support\Facades\Mail::raw(
                "Hello,\n\n" .
                "This is a test notification from the {$appName} configuration manager.\n\n" .
                "Configuration details verified:\n" .
                "• Host: {$host}\n" .
                "• Port: {$port}\n" .
                "• Encryption: {$encryption}\n" .
                "• From Address: {$fromAddr}\n" .
                "• From Name: {$fromName}\n" .
                "• Dispatched At: " . now()->toRfc2822String() . "\n\n" .
                "Your SMTP relay gateway is operational and ready for production deliverables.",
                function ($message) use ($recipient, $appName, $fromAddr, $fromName) {
                    $message->to($recipient)
                        ->from($fromAddr, $fromName)
                        ->subject("[{$appName}] SMTP Gateway Verification Successful");
                }
            );

            $this->testMailStatus = "Test email successfully dispatched to {$recipient}!";
            $this->dispatch('toast', message: 'Test email dispatched successfully!', type: 'success');
        } catch (\Throwable $e) {
            $this->testMailError = "SMTP Delivery Notice: " . $e->getMessage();
            $this->dispatch('toast', message: 'SMTP error: ' . $e->getMessage(), type: 'error');
        }
    }

    private function updateEnvMailSettings(): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath))
            return;

        $content = file_get_contents($envPath);

        $encryption = strtolower($this->smtpEncryption);
        $port = (int) ($this->smtpPort ?: (($encryption === 'ssl') ? 465 : 587));

        $replacements = [
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => $this->smtpHost,
            'MAIL_PORT' => $port,
            'MAIL_USERNAME' => $this->smtpUser,
            'MAIL_PASSWORD' => '"' . str_replace('"', '\"', $this->smtpPass) . '"',
            'MAIL_ENCRYPTION' => ($encryption === 'none') ? 'null' : $encryption,
            'MAIL_FROM_ADDRESS' => '"' . $this->mailFromAddress . '"',
            'MAIL_FROM_NAME' => '"' . $this->mailFromName . '"',
        ];

        foreach ($replacements as $key => $value) {
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        // Clean up legacy MAIL_SCHEME or MAIL_URL lines that break Symfony Mailer transport parsing
        $content = preg_replace('/^MAIL_SCHEME=.*/m', 'MAIL_SCHEME=null', $content);
        if (preg_match('/^MAIL_URL=.*/m', $content)) {
            $content = preg_replace('/^MAIL_URL=.*/m', '# MAIL_URL=', $content);
        }

        file_put_contents($envPath, $content);

        // Apply clean dynamic runtime config to Laravel Mailer Manager & purge cached transport instance
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp' => [
                'transport' => 'smtp',
                'host' => $this->smtpHost,
                'port' => $port,
                'encryption' => ($encryption === 'none') ? null : $encryption,
                'username' => $this->smtpUser,
                'password' => $this->smtpPass,
                'timeout' => 10,
                'local_domain' => parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST),
                'stream' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
            'mail.from' => [
                'address' => $this->mailFromAddress,
                'name' => $this->mailFromName,
            ],
        ]);

        \Illuminate\Support\Facades\Mail::purge('smtp');
    }

    public function render()
    {
        return view('livewire.settings-manager');
    }
}
