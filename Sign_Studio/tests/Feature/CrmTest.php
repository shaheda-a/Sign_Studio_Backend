<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerSite;
use App\Models\Architect;
use App\Models\Contractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create([
            'name'    => 'Test Branch',
            'code'    => 'TB01',
            'address' => 'Test Address',
        ]);

        $department = Department::create([
            'branch_id' => $this->branch->id,
            'name'      => 'Test Dept',
            'code'      => 'TD01',
        ]);

        $user = User::create([
            'branch_id'       => $this->branch->id,
            'department_id'   => $department->id,
            'employee_code'   => 'EMP001',
            'name'            => 'Test User',
            'email'           => 'test@example.com',
            'phone'           => '1234567890',
            'designation'     => 'Tester',
            'password'        => 'password',
            'date_of_joining' => '2026-01-01',
            'is_active'       => 1,
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        $this->token = $response->json('data.token');
    }

    public function test_can_create_customer(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/customers', [
                'branch_id' => $this->branch->id,
                'name'      => 'Test Customer Corp',
                'email'     => 'customer@test.com',
                'phone'     => '9999999999',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Customer Corp');
    }

    public function test_can_list_customers(): void
    {
        Customer::create([
            'branch_id' => $this->branch->id,
            'name'      => 'Test Customer Corp',
            'email'     => 'customer@test.com',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'items',
                    'meta',
                ]
            ]);
    }

    public function test_can_create_customer_contact(): void
    {
        $customer = Customer::create([
            'branch_id' => $this->branch->id,
            'name'      => 'Test Customer Corp',
            'email'     => 'customer@test.com',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/customer-contacts', [
                'customer_id' => $customer->id,
                'name'        => 'Jane Doe',
                'phone'       => '8888888888',
                'is_primary'  => 1,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Jane Doe');
    }

    public function test_can_create_architect(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/architects', [
                'name'                  => 'Architect Sanjay',
                'firm_name'             => 'Sanjay Associates',
                'commission_percentage' => 4.50,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Architect Sanjay');
    }
}
