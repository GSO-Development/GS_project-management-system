<?php

use App\Models\User;
use App\Livewire\ProjectManagerDashboard;
use Livewire\Livewire;

test('user dashboard renders project manager dashboard component successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(ProjectManagerDashboard::class)
        ->assertStatus(200)
        ->assertSee('Task Progress')
        ->assertSee('Overall Project Task Completion');
});

test('user dashboard can toggle chart period between week and month', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(ProjectManagerDashboard::class)
        ->assertSet('chartPeriod', 'week')
        ->call('setChartPeriod', 'month')
        ->assertSet('chartPeriod', 'month')
        ->assertStatus(200)
        ->call('setChartPeriod', 'week')
        ->assertSet('chartPeriod', 'week')
        ->assertStatus(200);
});
