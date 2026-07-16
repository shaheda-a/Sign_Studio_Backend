<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Models\Customer;
use App\Models\LeadSource;
use App\Models\PipelineStage;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private User $user;
    private Customer $customer;
    private LeadSource $source;
    private PipelineStage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $branch = Branch::create([
            'name'    => 'Test Branch',
            'code'    => 'TB01',
            'address' => 'Test Address',
        ]);

        $department = Department::create([
            'branch_id' => $branch->id,
            'name'      => 'Test Dept',
            'code'      => 'TD01',
        ]);

        $this->user = User::create([
            'branch_id'       => $branch->id,
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

        $this->customer = Customer::create([
            'branch_id' => $branch->id,
            'name'      => 'Test Customer Corp',
            'email'     => 'customer@test.com',
        ]);

        $this->source = LeadSource::create([
            'name' => 'Test Source',
            'code' => 'TEST_SRC',
        ]);

        $this->stage = PipelineStage::create([
            'name'       => 'Test Stage',
            'code'       => 'TEST_STAGE',
            'sort_order' => 1,
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        $this->token = $response->json('data.token');
    }

    public function test_can_create_lead(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/leads', [
                'customer_id'       => $this->customer->id,
                'source_id'         => $this->source->id,
                'pipeline_stage_id' => $this->stage->id,
                'title'             => 'Signage Project for Mall',
                'estimated_value'   => 25000.00,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Signage Project for Mall');

        $this->assertDatabaseHas('leads', [
            'title' => 'Signage Project for Mall',
        ]);
    }

    public function test_can_assign_lead(): void
    {
        $lead = Lead::create([
            'customer_id'       => $this->customer->id,
            'source_id'         => $this->source->id,
            'pipeline_stage_id' => $this->stage->id,
            'lead_number'       => 'LD-9909',
            'title'             => 'Signage Project for Mall',
        ]);

        $newUser = User::create([
            'branch_id'       => $this->user->branch_id,
            'department_id'   => $this->user->department_id,
            'employee_code'   => 'EMP002',
            'name'            => 'New User',
            'email'           => 'newuser@example.com',
            'phone'           => '1234567891',
            'designation'     => 'Sales Representative',
            'password'        => 'password',
            'date_of_joining' => '2026-01-01',
            'is_active'       => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("/api/leads/{$lead->id}/assign", [
                'assigned_to' => $newUser->id,
                'reason'      => 'Load balancing',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('leads', [
            'id'          => $lead->id,
            'assigned_to' => $newUser->id,
        ]);

        $this->assertDatabaseHas('lead_assignments', [
            'lead_id'     => $lead->id,
            'assigned_to' => $newUser->id,
        ]);
    }

    public function test_can_transition_pipeline_stage(): void
    {
        $lead = Lead::create([
            'customer_id'       => $this->customer->id,
            'source_id'         => $this->source->id,
            'pipeline_stage_id' => $this->stage->id,
            'lead_number'       => 'LD-9910',
            'title'             => 'Signage Project for Mall',
        ]);

        $newStage = PipelineStage::create([
            'name'       => 'Proposal Sent',
            'code'       => 'PROPOSAL_SENT',
            'sort_order' => 2,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("/api/leads/{$lead->id}/transition-stage", [
                'pipeline_stage_id' => $newStage->id,
                'notes'             => 'Proposal emailed to client',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('leads', [
            'id'                => $lead->id,
            'pipeline_stage_id' => $newStage->id,
        ]);

        $this->assertDatabaseHas('lead_pipeline_history', [
            'lead_id'     => $lead->id,
            'to_stage_id' => $newStage->id,
        ]);
    }
}
