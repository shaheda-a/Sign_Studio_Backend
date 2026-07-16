<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

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

        $this->user = User::create([
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
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token',
                ]
            ]);
    }

    public function test_user_cannot_login_with_incorrect_password(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_access_profile(): void
    {
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJsonPath('data.email', 'test@example.com');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Logged out successfully');

        $this->assertDatabaseCount('personal_access_tokens', 0);

        // Reset resolved guards in testing container to clear in-memory auth state cache
        \Illuminate\Support\Facades\Auth::forgetGuards();

        // Verify request with old token fails now
        $profileResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/profile');

        $profileResponse->assertStatus(401);
    }
}
