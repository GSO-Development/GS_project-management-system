<?php

namespace Tests\Feature;

use App\Livewire\ProjectCreate;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Typing 2+ characters in a Project Team dropdown search box also searches Azure AD.
 * People not already listed appear inline with a one-click "Add & assign" / "Assign" button.
 */
class ProjectCreateAzureSearchTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Subsidiary $subsidiary;
    protected Subsidiary $otherSubsidiary;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('super_admin', 'web');

        config([
            'services.azure.tenant'        => 'tenant-id',
            'services.azure.client_id'     => 'client-id',
            'services.azure.client_secret' => 'secret',
        ]);

        $this->subsidiary      = Subsidiary::create(['name' => 'George Steuart Health', 'code' => 'GSH']);
        $this->otherSubsidiary = Subsidiary::create(['name' => 'Remote Holdings', 'code' => 'RMH']);

        $this->admin = User::factory()->create(['subsidiary_id' => $this->subsidiary->id]);
        $this->admin->assignRole('super_admin');
    }

    protected function fakeAzure(array $users): void
    {
        Http::fake([
            'login.microsoftonline.com/*' => Http::response(['access_token' => 'fake-token', 'expires_in' => 3600], 200),
            'graph.microsoft.com/*'       => Http::response(['value' => $users], 200),
        ]);
    }

    protected function azurePerson(string $id = 'az-1', string $name = 'Amal Perera', string $mail = 'amal@example.com'): array
    {
        return [
            'id' => $id, 'displayName' => $name, 'mail' => $mail,
            'userPrincipalName' => $mail, 'mobilePhone' => '0771234567',
            'department' => null, 'companyName' => null,
        ];
    }

    protected function createPage()
    {
        return Livewire::actingAs($this->admin)
            ->test(ProjectCreate::class)
            ->set('subsidiary_id', $this->subsidiary->id);
    }

    public function test_typing_in_a_dropdown_searches_azure_and_one_click_adds_and_assigns(): void
    {
        $this->fakeAzure([$this->azurePerson()]);

        $component = $this->createPage()
            ->set('ownerSearch', 'amal')
            ->assertSet('azureRole', 'owner')
            ->assertSee('Add &amp; assign', false);

        $this->assertCount(1, $component->get('azureResults'));
        $this->assertFalse($component->get('azureResults')[0]['in_system']);

        $component->call('addAzureUser', 0)
            ->assertSet('azureRole', null)
            ->assertSet('ownerSearch', '')
            ->assertDispatched('toast');

        $created = User::where('email', 'amal@example.com')->first();
        $this->assertNotNull($created, 'Azure user must be created in the system (shows on Participants page).');
        $this->assertSame('az-1', $created->azure_id);
        $this->assertTrue((bool) $created->is_active);
        $this->assertFalse((bool) $created->must_change_password);
        $this->assertContains((string) $created->id, array_map('strval', $component->get('owner_ids')));
    }

    public function test_azure_person_can_be_set_as_project_manager(): void
    {
        $this->fakeAzure([$this->azurePerson('az-2', 'Nimali Silva', 'nimali@example.com')]);

        $component = $this->createPage()
            ->set('leaderSearch', 'nim')
            ->call('addAzureUser', 0);

        $created = User::where('email', 'nimali@example.com')->first();
        $this->assertNotNull($created);
        $this->assertSame($created->id, $component->get('project_manager_id'));
        $component->assertSeeHtml('Nimali Silva');
    }

    public function test_people_already_listed_in_the_dropdown_are_not_repeated_from_azure(): void
    {
        User::factory()->create([
            'name' => 'Amal Perera', 'email' => 'amal@example.com',
            'subsidiary_id' => $this->subsidiary->id, 'is_active' => true,
        ]);

        $this->fakeAzure([$this->azurePerson()]);

        $component = $this->createPage()->set('sponsorSearch', 'amal');

        $this->assertSame([], $component->get('azureResults'));
    }

    public function test_existing_user_outside_the_list_is_reused_not_duplicated(): void
    {
        $existing = User::factory()->create([
            'name' => 'Amal Perera', 'email' => 'amal@example.com',
            'subsidiary_id' => $this->otherSubsidiary->id, 'is_active' => true,
        ]);

        $this->fakeAzure([$this->azurePerson()]);

        $component = $this->createPage()->set('sponsorSearch', 'amal');

        $this->assertTrue($component->get('azureResults')[0]['in_system']);
        $component->assertSee('Assign');

        $component->call('addAzureUser', 0);

        $this->assertSame(1, User::where('email', 'amal@example.com')->count());
        $this->assertContains((string) $existing->id, array_map('strval', $component->get('sponsor_ids')));
        $this->assertSame('az-1', $existing->fresh()->azure_id, 'Azure identity should be linked to the existing local user.');
    }

    public function test_person_already_holding_another_role_is_not_moved(): void
    {
        $existing = User::factory()->create([
            'name' => 'Amal Perera', 'email' => 'amal@example.com',
            'subsidiary_id' => $this->otherSubsidiary->id, 'is_active' => true,
        ]);

        $this->fakeAzure([$this->azurePerson()]);

        $component = $this->createPage()
            ->set('sponsor_ids', [(string) $existing->id])
            ->set('ownerSearch', 'amal')
            ->call('addAzureUser', 0);

        $this->assertSame([], $component->get('owner_ids'));
        $this->assertStringContainsString('already assigned as Project Sponsor', (string) $component->get('azureError'));
    }

    public function test_admin_accounts_cannot_be_assigned_through_azure_search(): void
    {
        $this->fakeAzure([$this->azurePerson('az-9', $this->admin->name, $this->admin->email)]);

        $component = $this->createPage()->set('participantSearch', 'ad');

        $this->assertSame('Admin account', $component->get('azureResults')[0]['blocked_reason']);

        $component->call('addAzureUser', 0);
        $this->assertSame([], $component->get('selected_participant_ids'));
    }

    public function test_new_user_appears_in_the_dropdown_even_for_another_subsidiary(): void
    {
        $this->fakeAzure([$this->azurePerson('az-5', 'Kasun Fernando', 'kasun@example.com')]);

        $component = Livewire::actingAs($this->admin)
            ->test(ProjectCreate::class)
            ->set('subsidiary_id', $this->otherSubsidiary->id)
            ->set('participantSearch', 'kas')
            ->call('addAzureUser', 0);

        $this->assertNotNull(User::where('email', 'kasun@example.com')->first());
        $component->assertSee('Kasun Fernando');
    }

    public function test_short_queries_skip_azure_and_missing_configuration_is_reported(): void
    {
        $this->fakeAzure([$this->azurePerson()]);

        $this->createPage()
            ->set('ownerSearch', 'a')
            ->assertSet('azureResults', []);

        config(['services.azure.client_secret' => null]);

        $this->createPage()
            ->set('ownerSearch', 'amal')
            ->assertSet('azureResults', [])
            ->assertSet('azureError', 'Azure AD is not configured. Please set the Azure tenant / client credentials first.');
    }

    public function test_unknown_custom_role_is_ignored(): void
    {
        $this->fakeAzure([$this->azurePerson()]);

        $this->createPage()
            ->set('customRoleSearch.not_a_role', 'amal')
            ->assertSet('azureRole', null);
    }
}
