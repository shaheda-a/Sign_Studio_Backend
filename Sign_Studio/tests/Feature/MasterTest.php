<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $branch = Branch::create([
            'name' => 'Test Branch',
            'code' => 'TB01',
            'address' => 'Test Address',
        ]);

        $department = Department::create([
            'branch_id' => $branch->id,
            'name' => 'Test Dept',
            'code' => 'TD01',
        ]);

        $user = User::create([
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'employee_code' => 'EMP001',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'designation' => 'Tester',
            'password' => 'password',
            'date_of_joining' => '2026-01-01',
            'is_active' => 1,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->token = $response->json('data.token');
    }

    public function test_can_list_branches(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/branches');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'items',
                    'meta',
                ]
            ]);
    }

    public function test_can_create_branch(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/branches', [
                'name' => 'New Branch',
                'code' => 'NB02',
                'address' => 'New Address',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'New Branch');
    }

    public function test_can_update_branch(): void
    {
        $branch = Branch::first();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/branches/' . $branch->id, [
                'name' => 'Updated Branch Name',
                'code' => 'TB01',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Branch Name');
    }

    public function test_can_delete_branch(): void
    {
        $branch = Branch::create([
            'name' => 'Branch to Delete',
            'code' => 'BD99',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/branches/' . $branch->id);

        $response->assertStatus(200);

        // Verify soft deleted
        $this->assertSoftDeleted('branches', [
            'id' => $branch->id,
        ]);
    }
}
