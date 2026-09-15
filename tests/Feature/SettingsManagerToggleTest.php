<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsManagerToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_toggling_overdue_email_alerts_dispatches_toast_and_persists_setting(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@georgesteuart.com',
        ]);
        $admin->assignRole('super_admin');

        Livewire::actingAs($admin)
            ->test(\App\Livewire\SettingsManager::class)
            ->set('enableOverdueEmailAlerts', false)
            ->assertDispatched('toast', function ($name, $params) {
                return str_contains($params['message'], 'Automated overdue task email alerts Disabled');
            })
            ->set('enableOverdueEmailAlerts', true)
            ->assertDispatched('toast', function ($name, $params) {
                return str_contains($params['message'], 'Automated overdue task email alerts Enabled');
            });

        $this->assertEquals('true', SystemSetting::where('key', 'enable_overdue_email_alerts')->value('value'));
    }
}
