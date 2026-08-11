<?php

namespace App\Livewire;

use App\Models\SystemSetting;
use Livewire\Component;

class SettingsManager extends Component
{
    public string $appName = 'NexusPM';
    public string $wbsCalculationMethod = 'weighted';
    public bool $enableEmailNotifications = false;

    public function mount()
    {
        $this->appName = SystemSetting::where('key', 'app_name')->value('value') ?? 'NexusPM';
        $this->wbsCalculationMethod = SystemSetting::where('key', 'wbs_calculation_method')->value('value') ?? 'weighted';
    }

    public function saveSettings()
    {
        SystemSetting::updateOrCreate(['key' => 'app_name'], ['value' => $this->appName, 'group' => 'general']);
        SystemSetting::updateOrCreate(['key' => 'wbs_calculation_method'], ['value' => $this->wbsCalculationMethod, 'group' => 'wbs']);

        $this->dispatch('toast', message: 'System settings saved!', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings-manager');
    }
}
