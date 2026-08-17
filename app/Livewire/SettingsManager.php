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
    public string $smtpUser = 'prathibhajay098@gmail.com';
    public string $smtpPass = 'whvcknxyueynxiwk';
    public string $smtpEncryption = 'tls';
    public string $mailFromAddress = 'prathibhajay098@gmail.com';
    public string $mailFromName = 'GS Project Management';

    // Security & Password Rules
    public bool $enforcePasswordComplexity = true;
    public int $sessionTimeout = 120;

    // Azure AD SSO Integration
    public bool $enableAzureSso = true;
    public string $azureTenantId = 'common';
    public string $azureClientId = '';

    public function mount()
    {
        $this->appName = SystemSetting::where('key', 'app_name')->value('value') ?? 'GS NexusPM';
        $this->wbsCalculationMethod = SystemSetting::where('key', 'wbs_calculation_method')->value('value') ?? 'weighted';
        $this->enableEmailNotifications = filter_var(SystemSetting::where('key', 'enable_email_notifications')->value('value') ?? false, FILTER_VALIDATE_BOOLEAN);

        // Mail Server Settings — DB first, then .env fallback
        $this->smtpHost       = SystemSetting::where('key', 'smtp_host')->value('value')
                                ?? env('MAIL_HOST', 'smtp.gmail.com');
        $this->smtpPort       = (int) (SystemSetting::where('key', 'smtp_port')->value('value')
                                ?? env('MAIL_PORT', 587));
        $this->smtpUser       = SystemSetting::where('key', 'smtp_user')->value('value')
                                ?? env('MAIL_USERNAME', 'prathibhajay098@gmail.com');
        $this->smtpPass       = SystemSetting::where('key', 'smtp_pass')->value('value')
                                ?? env('MAIL_PASSWORD', 'whvcknxyueynxiwk');
        $this->smtpEncryption = SystemSetting::where('key', 'smtp_encryption')->value('value')
                                ?? env('MAIL_SCHEME', 'tls');
        $this->mailFromAddress = SystemSetting::where('key', 'mail_from_address')->value('value')
                                ?? env('MAIL_FROM_ADDRESS', 'prathibhajay098@gmail.com');
        $this->mailFromName   = SystemSetting::where('key', 'mail_from_name')->value('value')
                                ?? env('MAIL_FROM_NAME', 'GS Project Management');

        // Security Settings
        $this->enforcePasswordComplexity = filter_var(SystemSetting::where('key', 'enforce_password_complexity')->value('value') ?? true, FILTER_VALIDATE_BOOLEAN);
        $this->sessionTimeout = (int) (SystemSetting::where('key', 'session_timeout')->value('value') ?? 120);

        // Azure SSO Settings
        $this->enableAzureSso = filter_var(SystemSetting::where('key', 'enable_azure_sso')->value('value') ?? true, FILTER_VALIDATE_BOOLEAN);
        $this->azureTenantId = SystemSetting::where('key', 'azure_tenant_id')->value('value') ?? 'common';
        $this->azureClientId = SystemSetting::where('key', 'azure_client_id')->value('value') ?? '';
    }

    public function saveSettings()
    {
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

        // Also update .env file so settings persist across server restarts
        $this->updateEnvMailSettings();

        $this->dispatch('toast', message: 'System settings saved successfully!', type: 'success');
    }

    private function updateEnvMailSettings(): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) return;

        $content = file_get_contents($envPath);

        $encryption = strtolower($this->smtpEncryption);
        $port       = ($encryption === 'ssl') ? 465 : 587;
        $scheme     = ($encryption === 'ssl') ? 'smtps' : 'null';

        // Build DSN URL with SSL verify bypass (needed for Windows local dev)
        $encodedUser = urlencode($this->smtpUser);
        $encodedPass = urlencode($this->smtpPass);
        $urlScheme   = ($encryption === 'ssl') ? 'smtps' : 'smtp';
        $mailUrl     = "\"{$urlScheme}://{$encodedUser}:{$encodedPass}@{$this->smtpHost}:{$port}?verify_peer=0\"";

        $replacements = [
            'MAIL_MAILER'       => 'smtp',
            'MAIL_SCHEME'       => $scheme,
            'MAIL_HOST'         => $this->smtpHost,
            'MAIL_PORT'         => $port,
            'MAIL_USERNAME'     => $this->smtpUser,
            'MAIL_PASSWORD'     => $this->smtpPass,
            'MAIL_FROM_ADDRESS' => $this->mailFromAddress,
            'MAIL_FROM_NAME'    => '"' . $this->mailFromName . '"',
        ];

        foreach ($replacements as $key => $value) {
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        // Update MAIL_URL with new credentials + SSL bypass
        if (preg_match('/^MAIL_URL=.*/m', $content)) {
            $content = preg_replace('/^MAIL_URL=.*/m', "MAIL_URL={$mailUrl}", $content);
        } else {
            $content .= "\nMAIL_URL={$mailUrl}";
        }

        file_put_contents($envPath, $content);

        // Apply to current runtime immediately via DSN URL
        config([
            'mail.mailers.smtp.url'      => rtrim($mailUrl, '"'),
            'mail.mailers.smtp.host'     => $this->smtpHost,
            'mail.mailers.smtp.port'     => $port,
            'mail.mailers.smtp.username' => $this->smtpUser,
            'mail.mailers.smtp.password' => $this->smtpPass,
            'mail.mailers.smtp.scheme'   => ($scheme === 'null') ? null : $scheme,
            'mail.mailers.smtp.timeout'  => 10,
            'mail.from.address'          => $this->mailFromAddress,
            'mail.from.name'             => $this->mailFromName,
        ]);
    }

    public function render()
    {
        return view('livewire.settings-manager');
    }
}
