<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ForcePasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_system_created_user_with_must_change_password_is_redirected_to_force_password_change(): void
    {
        $user = User::factory()->create([
            'must_change_password' => true,
            'azure_id' => null, // Local system-created user
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/force-password-change');
    }

    public function test_system_created_user_cannot_access_other_routes_until_password_is_changed(): void
    {
        $user = User::factory()->create([
            'must_change_password' => true,
            'azure_id' => null,
        ]);

        $response = $this->actingAs($user)->get('/projects');

        $response->assertRedirect('/force-password-change');
    }

    public function test_user_can_change_password_and_is_redirected_to_dashboard_without_relogin(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword@123'),
            'must_change_password' => true,
            'azure_id' => null,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\ForcePasswordChange::class)
            ->set('new_password', 'NewSecurePassword@123')
            ->set('new_password_confirmation', 'NewSecurePassword@123')
            ->call('changePassword')
            ->assertRedirect(route('dashboard'));

        $user->refresh();

        $this->assertFalse((bool) $user->must_change_password);
        $this->assertTrue(Hash::check('NewSecurePassword@123', $user->password));
        $this->assertAuthenticatedAs($user); // Remains authenticated without re-login
    }

    public function test_user_with_password_already_changed_is_redirected_away_from_force_password_change_screen(): void
    {
        $user = User::factory()->create([
            'must_change_password' => false,
            'azure_id' => null,
        ]);

        $response = $this->actingAs($user)->get('/force-password-change');

        $response->assertRedirect('/dashboard');
    }
}
