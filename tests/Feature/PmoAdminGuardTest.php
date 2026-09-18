<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\PmoAdminGuard;
use Livewire\Livewire;
use App\Livewire\UserManager;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PmoAdminGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate("super_admin", "web");
    }

    public function test_last_pmo_admin_cannot_be_deleted_or_demoted()
    {
        $admin = User::factory()->create();
        $admin->assignRole("super_admin");

        $this->assertEquals(1, PmoAdminGuard::getPmoAdminCount());
        $this->assertTrue(PmoAdminGuard::isLastAdmin($admin));

        // Test Livewire single delete block
        Livewire::actingAs($admin)
            ->test(UserManager::class)
            ->call("deleteUser", $admin->id)
            ->assertDispatched("toast");

        $this->assertDatabaseHas("users", ["id" => $admin->id, "deleted_at" => null]);

        // Test Livewire edit role block
        Livewire::actingAs($admin)
            ->test(UserManager::class)
            ->set("editingId", $admin->id)
            ->set("name", $admin->name)
            ->set("email", $admin->email)
            ->set("role", "regular_user")
            ->call("save")
            ->assertDispatched("toast");

        $this->assertTrue($admin->fresh()->hasRole("super_admin"));
    }

    public function test_admin_can_be_deleted_if_another_pmo_admin_exists()
    {
        $admin1 = User::factory()->create(["email" => "admin1_test@georgesteuart.com"]);
        $admin1->assignRole("super_admin");

        $admin2 = User::factory()->create(["email" => "admin2_test@georgesteuart.com"]);
        $admin2->assignRole("super_admin");

        $this->assertGreaterThanOrEqual(2, PmoAdminGuard::getPmoAdminCount());
        $this->assertFalse(PmoAdminGuard::isLastAdmin($admin1));

        // Delete admin1
        Livewire::actingAs($admin2)
            ->test(UserManager::class)
            ->call("deleteUser", $admin1->id);

        $this->assertSoftDeleted("users", ["id" => $admin1->id]);
    }

    public function test_deleted_user_email_can_be_recreated()
    {
        $admin1 = User::factory()->create(["email" => "recreate_test@georgesteuart.com"]);
        $admin1->assignRole("super_admin");

        $admin2 = User::factory()->create(["email" => "admin2_test@georgesteuart.com"]);
        $admin2->assignRole("super_admin");

        // Delete admin1
        $admin1->delete();
        $this->assertSoftDeleted("users", ["id" => $admin1->id]);

        // Recreate account with same email
        Livewire::actingAs($admin2)
            ->test(UserManager::class)
            ->set("name", "Recreated PMO Admin")
            ->set("email", "recreate_test@georgesteuart.com")
            ->set("password", "Password@123")
            ->set("role", "super_admin")
            ->call("save")
            ->assertHasNoErrors();

        $this->assertDatabaseHas("users", [
            "id" => $admin1->id,
            "name" => "Recreated PMO Admin",
            "email" => "recreate_test@georgesteuart.com",
            "deleted_at" => null
        ]);
    }

    public function test_last_pmo_admin_status_cannot_be_toggled_or_disabled()
    {
        $admin = User::factory()->create();
        $admin->assignRole("super_admin");

        // Try to toggle status of the last admin
        Livewire::actingAs($admin)
            ->test(UserManager::class)
            ->call("toggleStatus", $admin->id)
            ->assertDispatched("toast");

        $this->assertTrue((bool) $admin->fresh()->is_active);

        // Verify edit sets isEditingLastAdmin to true
        Livewire::actingAs($admin)
            ->test(UserManager::class)
            ->call("edit", $admin)
            ->assertSet("isEditingLastAdmin", true);
    }
}
