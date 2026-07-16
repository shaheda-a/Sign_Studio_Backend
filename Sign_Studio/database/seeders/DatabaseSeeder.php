<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\StageMaster;
use App\Models\StatusMaster;
use App\Models\ChecklistMaster;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerSite;
use App\Models\CustomerReferral;
use App\Models\Architect;
use App\Models\Contractor;
use App\Models\LeadSource;
use App\Models\PipelineStage;
use App\Models\Lead;
use App\Models\LeadAssignment;
use App\Models\LeadPipelineHistory;
use App\Models\LeadStatusHistory;
use App\Models\LeadActivity;
use App\Models\LeadFollowup;
use App\Models\LeadScore;
use App\Models\LeadValidation;
use App\Models\CallLog;
use App\Models\BookingToken;
use App\Models\SiteVisit;
use App\Models\SiteMeasurement;
use App\Models\SitePhoto;
use App\Models\SiteChecklist;
use App\Models\GpsTrackingLog;
use App\Models\VisitProof;
use App\Models\Design;
use App\Models\DesignStatusHistory;
use App\Models\DesignRevision;
use App\Models\DesignFile;
use App\Models\DesignApproval;
use App\Models\SampleApproval;
use App\Models\MaterialApproval;
use App\Models\FinishApproval;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\PaymentLink;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\JobCard;
use App\Models\OrderValidation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Branches
        $branchMain = Branch::create([
            'name'      => 'Main Head Office',
            'code'      => 'HQ01',
            'address'   => '123 Main Street, Suite A, Metro City, State One - 123456',
            'is_active' => 1,
        ]);

        $branchWest = Branch::create([
            'name'      => 'West Wing Branch',
            'code'      => 'WB02',
            'address'   => '456 West Boulevard, West Town, State Two - 654321',
            'is_active' => 1,
        ]);

        // 2. Seed Departments
        $deptMgmt = Department::create([
            'branch_id' => $branchMain->id,
            'name'      => 'Management',
            'code'      => 'MGMT',
            'is_active' => 1,
        ]);

        $deptSales = Department::create([
            'branch_id' => $branchMain->id,
            'name'      => 'Sales Coordinator',
            'code'      => 'SALES',
            'is_active' => 1,
        ]);

        $deptDesign = Department::create([
            'branch_id' => $branchMain->id,
            'name'      => 'Design & Survey',
            'code'      => 'DSGN',
            'is_active' => 1,
        ]);

        $deptAccounts = Department::create([
            'branch_id' => $branchMain->id,
            'name'      => 'Accounts',
            'code'      => 'ACCT',
            'is_active' => 1,
        ]);

        // 3. Seed Roles & Permissions
        $permissions = [
            // Branches
            ['name' => 'view branches', 'module' => 'Branches'],
            ['name' => 'create branches', 'module' => 'Branches'],
            ['name' => 'update branches', 'module' => 'Branches'],
            ['name' => 'delete branches', 'module' => 'Branches'],
            ['name' => 'restore branches', 'module' => 'Branches'],

            // Departments
            ['name' => 'view departments', 'module' => 'Departments'],
            ['name' => 'create departments', 'module' => 'Departments'],
            ['name' => 'update departments', 'module' => 'Departments'],
            ['name' => 'delete departments', 'module' => 'Departments'],
            ['name' => 'restore departments', 'module' => 'Departments'],

            // Users
            ['name' => 'view users', 'module' => 'Users'],
            ['name' => 'create users', 'module' => 'Users'],
            ['name' => 'update users', 'module' => 'Users'],
            ['name' => 'delete users', 'module' => 'Users'],
            ['name' => 'restore users', 'module' => 'Users'],

            // Masters
            ['name' => 'view masters', 'module' => 'Masters'],
            ['name' => 'create masters', 'module' => 'Masters'],
            ['name' => 'update masters', 'module' => 'Masters'],
            ['name' => 'delete masters', 'module' => 'Masters'],
            ['name' => 'restore masters', 'module' => 'Masters'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['module' => $perm['module'], 'created_by' => 1]
            );
        }

        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'web'],
            ['created_by' => 1]
        );
        
        $salesRole = Role::firstOrCreate(
            ['name' => 'Sales Coordinator', 'guard_name' => 'web'],
            ['created_by' => 1]
        );

        $designRole = Role::firstOrCreate(
            ['name' => 'Design & Survey', 'guard_name' => 'web'],
            ['created_by' => 1]
        );

        // Assign all permissions to Super Admin
        $superAdminRole->syncPermissions(Permission::all());

        // 4. Seed Default Admin User
        $adminUser = User::create([
            'branch_id'       => $branchMain->id,
            'department_id'   => $deptMgmt->id,
            'employee_code'   => 'EMP001',
            'name'            => 'System Administrator',
            'email'           => 'admin@signstudio.com',
            'phone'           => '1234567890',
            'designation'     => 'Chief Executive Officer',
            'password'        => 'password', // Hashed automatically by cast if configured, or mutator. Let's make sure
            'date_of_joining' => '2026-01-01',
            'is_active'       => 1,
            'created_by'      => 1,
        ]);

        $adminUser->assignRole($superAdminRole);

        // 5. Seed sample Stage Masters
        StageMaster::create([
            'module'     => 'Leads',
            'stage_name' => 'Lead Received',
            'stage_code' => 'LEAD_REC',
            'sort_order' => 1,
            'is_active'  => 1,
            'created_by' => $adminUser->id,
        ]);

        StageMaster::create([
            'module'     => 'Leads',
            'stage_name' => 'Requirements Validated',
            'stage_code' => 'REQ_VAL',
            'sort_order' => 2,
            'is_active'  => 1,
            'created_by' => $adminUser->id,
        ]);

        // 6. Seed sample Status Masters
        StatusMaster::create([
            'module'      => 'Leads',
            'status_name' => 'Pending Assignment',
            'status_code' => 'PENDING_ASSIGN',
            'color'       => '#ff9800',
            'sort_order'  => 1,
            'is_active'   => 1,
            'created_by'  => $adminUser->id,
        ]);

        StatusMaster::create([
            'module'      => 'Leads',
            'status_name' => 'Contacted',
            'status_code' => 'CONTACTED',
            'color'       => '#2196f3',
            'sort_order'  => 2,
            'is_active'   => 1,
            'created_by'  => $adminUser->id,
        ]);

        // 7. Seed sample Checklist Masters
        ChecklistMaster::create([
            'module'       => 'Design & Survey',
            'item_name'    => 'Site measurements verified',
            'is_mandatory' => 1,
            'is_active'    => 1,
            'created_by'   => $adminUser->id,
        ]);

        ChecklistMaster::create([
            'module'       => 'Design & Survey',
            'item_name'    => 'Access route photographed',
            'is_mandatory' => 0,
            'is_active'    => 1,
            'created_by'   => $adminUser->id,
        ]);

        // 8. Seed Customers
        $customer1 = Customer::create([
            'branch_id'       => $branchMain->id,
            'name'            => 'Acme Corporation',
            'email'           => 'procurement@acme.com',
            'phone'           => '9876543210',
            'alternate_phone' => '9876543211',
            'gstin'           => '27AAAAA1111A1Z1',
            'billing_address' => '101 Industrial Area, Phase II',
            'billing_city'    => 'Mumbai',
            'billing_state'   => 'Maharashtra',
            'billing_pincode' => '400001',
            'is_active'       => 1,
            'created_by'      => $adminUser->id,
        ]);

        $customer2 = Customer::create([
            'branch_id'       => $branchMain->id,
            'name'            => 'Wayne Enterprises',
            'email'           => 'contact@wayne.com',
            'phone'           => '8888888888',
            'gstin'           => '27BBBBB2222B2Z2',
            'billing_address' => 'Wayne Tower, Central Business District',
            'billing_city'    => 'Mumbai',
            'billing_state'   => 'Maharashtra',
            'billing_pincode' => '400021',
            'is_active'       => 1,
            'created_by'      => $adminUser->id,
        ]);

        // 9. Seed Customer Contacts
        CustomerContact::create([
            'customer_id' => $customer1->id,
            'name'        => 'John Doe',
            'designation' => 'Procurement Manager',
            'email'       => 'john.doe@acme.com',
            'phone'       => '9876543201',
            'is_primary'  => 1,
            'created_by'  => $adminUser->id,
        ]);

        CustomerContact::create([
            'customer_id' => $customer1->id,
            'name'        => 'Jane Smith',
            'designation' => 'VP Operations',
            'email'       => 'jane.smith@acme.com',
            'phone'       => '9876543202',
            'is_primary'  => 0,
            'created_by'  => $adminUser->id,
        ]);

        // 10. Seed Customer Sites
        CustomerSite::create([
            'customer_id'    => $customer1->id,
            'site_name'      => 'Acme Warehouse',
            'site_address'   => 'Plot 55, Logistics Hub, JNPT Road',
            'site_city'      => 'Navi Mumbai',
            'site_state'     => 'Maharashtra',
            'site_pincode'   => '400707',
            'contact_person' => 'Site Supervisor',
            'contact_phone'  => '9999988888',
            'created_by'     => $adminUser->id,
        ]);

        // 11. Seed Customer Referrals
        CustomerReferral::create([
            'customer_id'          => $customer1->id,
            'referred_customer_id' => $customer2->id,
            'referral_date'        => '2026-06-15',
            'status'               => 'converted',
            'points_earned'        => 500,
            'created_by'           => $adminUser->id,
        ]);

        // 12. Seed Architects & Contractors
        Architect::create([
            'name'                  => 'Sanjay Puri Architects',
            'firm_name'             => 'Sanjay Puri & Associates',
            'email'                 => 'info@sanjaypuri.com',
            'phone'                 => '9123456780',
            'address'               => 'Studio 5, Design District, Worli',
            'commission_percentage' => 5.50,
            'is_active'             => 1,
            'created_by'            => $adminUser->id,
        ]);

        $contractor1 = Contractor::create([
            'name'                  => 'L&T Construction',
            'firm_name'             => 'Larsen & Toubro Ltd',
            'email'                 => 'contact@lntecc.com',
            'phone'                 => '9812345670',
            'address'               => 'L&T House, Ballard Estate',
            'commission_percentage' => 3.00,
            'is_active'             => 1,
            'created_by'            => $adminUser->id,
        ]);

        // 13. Seed Lead Sources
        $sourceGoogle = LeadSource::create([
            'name'       => 'Google Ads',
            'code'       => 'GOOGLE_ADS',
            'channel'    => 'Digital',
            'is_active'  => 1,
            'created_by' => $adminUser->id,
        ]);

        $sourceReferral = LeadSource::create([
            'name'       => 'Customer Referral',
            'code'       => 'CUST_REF',
            'channel'    => 'Organic',
            'is_active'  => 1,
            'created_by' => $adminUser->id,
        ]);

        // 14. Seed Pipeline Stages
        $stageNew = PipelineStage::create([
            'name'                => 'Lead Inflow',
            'code'                => 'INFLOW',
            'sort_order'          => 1,
            'probability_percent' => 10,
            'is_active'           => 1,
            'created_by'          => $adminUser->id,
        ]);

        $stageMeeting = PipelineStage::create([
            'name'                => 'Initial Meeting',
            'code'                => 'MEETING',
            'sort_order'          => 2,
            'probability_percent' => 30,
            'is_active'           => 1,
            'created_by'          => $adminUser->id,
        ]);

        // 15. Seed Leads
        $lead1 = Lead::create([
            'customer_id'         => $customer1->id,
            'source_id'           => $sourceGoogle->id,
            'assigned_to'         => $adminUser->id,
            'pipeline_stage_id'   => $stageNew->id,
            'lead_number'         => 'LD-889988',
            'title'               => 'Outdoor Signage for Corporate HQ',
            'status'              => 'new',
            'lead_score'          => 0,
            'estimated_value'     => 150000.00,
            'expected_close_date' => '2026-08-30',
            'created_by'          => $adminUser->id,
        ]);

        // 16. Seed Lead Assignments
        LeadAssignment::create([
            'lead_id'       => $lead1->id,
            'assigned_from' => null,
            'assigned_to'   => $adminUser->id,
            'reason'        => 'Initial routing',
            'assigned_at'   => now(),
            'created_by'    => $adminUser->id,
        ]);

        // 17. Seed Lead Pipeline History
        LeadPipelineHistory::create([
            'lead_id'       => $lead1->id,
            'from_stage_id' => null,
            'to_stage_id'   => $stageNew->id,
            'changed_by'    => $adminUser->id,
            'notes'         => 'Initial setup',
        ]);

        // 18. Seed Lead Status History
        LeadStatusHistory::create([
            'lead_id'     => $lead1->id,
            'from_status' => null,
            'to_status'   => 'new',
            'changed_by'  => $adminUser->id,
            'notes'       => 'Initial status',
        ]);

        // 19. Seed Lead Activities
        LeadActivity::create([
            'lead_id'        => $lead1->id,
            'user_id'        => $adminUser->id,
            'type'           => 'Call',
            'description'    => 'Introductory call to define parameters',
            'outcome'        => 'Positive - customer interested in site survey',
            'next_follow_up' => now()->addDays(2),
            'created_by'     => $adminUser->id,
        ]);

        // 20. Seed Lead Followups
        LeadFollowup::create([
            'lead_id'        => $lead1->id,
            'assigned_to'    => $adminUser->id,
            'follow_up_date' => now()->addDays(2),
            'follow_up_type' => 'Site Visit',
            'notes'          => 'Conduct measurements and take photos',
            'status'         => 'pending',
            'created_by'     => $adminUser->id,
        ]);

        // 21. Seed Lead Scores
        LeadScore::create([
            'lead_id'    => $lead1->id,
            'criteria'   => 'Budget Verified',
            'score'      => 40,
            'scored_by'  => $adminUser->id,
            'created_by' => $adminUser->id,
        ]);

        LeadScore::create([
            'lead_id'    => $lead1->id,
            'criteria'   => 'Authority Identified',
            'score'      => 30,
            'scored_by'  => $adminUser->id,
            'created_by' => $adminUser->id,
        ]);

        // Update the score cache on the Lead
        $lead1->update(['lead_score' => 70]);

        // 22. Seed Lead Validations
        LeadValidation::create([
            'lead_id'      => $lead1->id,
            'validated_by' => $adminUser->id,
            'status'       => 'approved',
            'remarks'      => 'Requirements match our capabilities perfectly.',
            'validated_at' => now(),
            'created_by'   => $adminUser->id,
        ]);

        // 23. Seed Call Logs
        CallLog::create([
            'lead_id'          => $lead1->id,
            'customer_id'      => $customer1->id,
            'user_id'          => $adminUser->id,
            'call_type'        => 'outbound',
            'duration_seconds' => 145,
            'recording_path'   => 'recordings/call-145-2026.mp3',
            'outcome'          => 'interested',
            'notes'            => 'Explained initial product catalog.',
            'called_at'        => now()->subHour(),
            'created_by'       => $adminUser->id,
        ]);

        // 24. Seed Booking Tokens
        BookingToken::create([
            'lead_id'         => $lead1->id,
            'customer_id'     => $customer1->id,
            'token_number'    => 'BT-990088',
            'amount'          => 10000.00,
            'payment_mode'    => 'UPI',
            'transaction_ref' => 'TXN9988776655',
            'status'          => 'cleared',
            'received_by'     => $adminUser->id,
            'received_at'     => now(),
            'created_by'      => $adminUser->id,
        ]);

        // 25. Seed Site Visits
        $siteVisit1 = SiteVisit::create([
            'lead_id'        => $lead1->id,
            'assigned_to'    => $adminUser->id,
            'visit_number'   => 'SV-001',
            'scheduled_at'   => now()->addDay(),
            'status'         => 'scheduled',
            'remarks'        => 'Initial site survey for corporate HQ signage',
            'created_by'     => $adminUser->id,
        ]);

        // 26. Seed Site Measurements
        SiteMeasurement::create([
            'site_visit_id' => $siteVisit1->id,
            'sign_position' => 'Main Entrance Fascia',
            'width'         => 12.50,
            'height'        => 3.00,
            'sq_ft'         => 37.50,
            'unit'          => 'feet',
            'notes'         => 'Above main entrance door',
            'created_by'    => $adminUser->id,
        ]);

        // 27. Seed Site Photos
        SitePhoto::create([
            'site_visit_id' => $siteVisit1->id,
            'file_path'     => 'site-photos/sv001-entrance.jpg',
            'file_type'     => 'image/jpeg',
            'caption'       => 'Front elevation view',
            'uploaded_at'   => now(),
            'created_by'    => $adminUser->id,
        ]);

        // 28. Seed Site Checklists
        SiteChecklist::create([
            'site_visit_id' => $siteVisit1->id,
            'item_name'     => 'Electrical point available',
            'is_completed'  => 1,
            'notes'         => 'Single phase 15A socket confirmed',
            'created_by'    => $adminUser->id,
        ]);

        // 29. Seed GPS Logs
        GpsTrackingLog::create([
            'site_visit_id' => $siteVisit1->id,
            'user_id'       => $adminUser->id,
            'latitude'      => 19.0760,
            'longitude'     => 72.8777,
            'accuracy'      => 5.5,
            'tracked_at'    => now(),
        ]);

        // 30. Seed Visit Proofs
        VisitProof::create([
            'site_visit_id'          => $siteVisit1->id,
            'proof_type'             => 'photo',
            'file_path'              => 'visit-proofs/sv001-proof.jpg',
            'customer_signature_path' => 'visit-proofs/sv001-signature.png',
            'notes'                  => 'Customer signed visit confirmation form',
            'uploaded_at'            => now(),
            'created_by'             => $adminUser->id,
        ]);

        // 31. Seed Designs
        $design1 = Design::create([
            'lead_id'       => $lead1->id,
            'assigned_to'   => $adminUser->id,
            'design_number' => 'DS-001',
            'title'         => 'Outdoor Fascia Sign - Corporate HQ',
            'status'        => 'in_progress',
            'due_date'      => now()->addDays(7)->toDateString(),
            'created_by'    => $adminUser->id,
        ]);

        // Seed Design Status History
        DesignStatusHistory::create([
            'design_id'   => $design1->id,
            'from_status' => null,
            'to_status'   => 'in_progress',
            'changed_by'  => $adminUser->id,
            'notes'       => 'Design work started',
        ]);

        // 32. Seed Design Revisions
        $revision1 = DesignRevision::create([
            'design_id'       => $design1->id,
            'revision_number' => 1,
            'feedback'        => 'Change font to bold and increase logo size by 20%',
            'requested_by'    => $adminUser->id,
            'due_date'        => now()->addDays(3)->toDateString(),
            'status'          => 'pending',
            'created_by'      => $adminUser->id,
        ]);

        // 33. Seed Design Files
        DesignFile::create([
            'design_id'   => $design1->id,
            'revision_id' => $revision1->id,
            'file_path'   => 'designs/ds001-v1.ai',
            'file_name'   => 'Corporate_HQ_Fascia_v1.ai',
            'file_type'   => 'application/illustrator',
            'file_size'   => 5242880,
            'is_final'    => 0,
            'uploaded_at' => now(),
            'created_by'  => $adminUser->id,
        ]);

        // 34. Seed Design Approvals
        DesignApproval::create([
            'design_id'         => $design1->id,
            'revision_id'       => $revision1->id,
            'type'              => 'internal',
            'status'            => 'approved',
            'customer_approved' => 0,
            'approved_by'       => $adminUser->id,
            'remarks'           => 'Internal design approved, send to customer',
            'approved_at'       => now(),
            'created_by'        => $adminUser->id,
        ]);

        // 35. Seed Sample Approvals
        SampleApproval::create([
            'design_id'         => $design1->id,
            'sample_photo_path' => 'samples/ds001-sample.jpg',
            'sent_to'           => $adminUser->id,
            'customer_approved' => 1,
            'remarks'           => 'Customer approved the sample',
            'approved_at'       => now(),
            'created_by'        => $adminUser->id,
        ]);

        // 36. Seed Material Approvals
        MaterialApproval::create([
            'design_id'         => $design1->id,
            'material_name'     => 'ACM Panel 3mm',
            'brand'             => 'Alubond',
            'customer_approved' => 1,
            'remarks'           => 'Customer approved Alubond ACM material',
            'approved_at'       => now(),
            'created_by'        => $adminUser->id,
        ]);

        // 37. Seed Finish Approvals
        FinishApproval::create([
            'design_id'         => $design1->id,
            'finish_type'       => 'Gloss Lamination',
            'customer_approved' => 1,
            'remarks'           => 'Gloss finish approved by customer',
            'approved_at'       => now(),
            'created_by'        => $adminUser->id,
        ]);

        // 38. Seed Quotations
        $quotation1 = Quotation::create([
            'lead_id'          => $lead1->id,
            'customer_id'      => $customer1->id,
            'design_id'        => $design1->id,
            'quote_number'     => 'QT-001',
            'version'          => 1,
            'sub_total'        => 150000.00,
            'discount_amount'  => 5000.00,
            'tax_amount'       => 26100.00,
            'grand_total'      => 171100.00,
            'validity_days'    => 30,
            'terms_conditions' => 'Payment: 50% advance, 50% on delivery. Warranty: 1 year on materials.',
            'notes'            => 'Includes all outdoor fascia signage as per site visit measurements.',
            'status'           => 'sent',
            'sent_at'          => now(),
            'created_by'       => $adminUser->id,
        ]);

        // 39. Seed Quotation Items
        $qItem1 = QuotationItem::create([
            'quotation_id'     => $quotation1->id,
            'description'      => 'ACM Panel Fascia Sign - Main Entrance (12.5 x 3 ft)',
            'qty'              => 1,
            'uom'              => 'Sqft',
            'unit_price'       => 120000.00,
            'discount_percent' => 0,
            'tax_rate'         => 18,
            'tax_amount'       => 21600.00,
            'total'            => 141600.00,
            'sort_order'       => 1,
            'created_by'       => $adminUser->id,
        ]);

        QuotationItem::create([
            'quotation_id'     => $quotation1->id,
            'description'      => 'LED Channel Letter Installation',
            'qty'              => 1,
            'uom'              => 'Job',
            'unit_price'       => 30000.00,
            'discount_percent' => 0,
            'tax_rate'         => 18,
            'tax_amount'       => 5400.00,
            'total'            => 35400.00,
            'sort_order'       => 2,
            'created_by'       => $adminUser->id,
        ]);

        // 40. Seed Payment Link
        PaymentLink::create([
            'quotation_id'   => $quotation1->id,
            'link_reference' => 'PAY-QL-9988',
            'amount'         => 85550.00,
            'gateway'        => 'Razorpay',
            'status'         => 'pending',
            'expires_at'     => now()->addDays(7),
            'created_by'     => $adminUser->id,
        ]);

        // 41. Seed Orders
        $order1 = Order::create([
            'quotation_id'    => $quotation1->id,
            'customer_id'     => $customer1->id,
            'branch_id'       => Branch::first()->id,
            'order_number'    => 'ORD-001',
            'total_amount'    => 171100.00,
            'advance_received' => 85550.00,
            'balance_amount'  => 85550.00,
            'delivery_date'   => now()->addDays(21)->toDateString(),
            'status'          => 'confirmed',
            'created_by'      => $adminUser->id,
        ]);

        // 42. Seed Order Items
        OrderItem::create([
            'order_id'          => $order1->id,
            'quotation_item_id' => $qItem1->id,
            'description'       => 'ACM Panel Fascia Sign - Main Entrance',
            'qty'               => 1,
            'unit_price'        => 120000.00,
            'total'             => 141600.00,
            'created_by'        => $adminUser->id,
        ]);

        // 43. Seed Job Card
        JobCard::create([
            'order_id'       => $order1->id,
            'job_card_number' => 'JC-001',
            'qr_code_data'   => json_encode(['order_id' => $order1->id, 'order_number' => 'ORD-001']),
            'notes'          => 'Priority production run. Ensure Alubond ACM approved material.',
            'created_by'     => $adminUser->id,
        ]);

        // 44. Seed Order Validations
        OrderValidation::create([
            'order_id'     => $order1->id,
            'department'   => 'Sales',
            'status'       => 'approved',
            'validated_by' => $adminUser->id,
            'remarks'      => 'All documents verified. Advance payment confirmed.',
            'validated_at' => now(),
            'created_by'   => $adminUser->id,
        ]);

        OrderValidation::create([
            'order_id'     => $order1->id,
            'department'   => 'Design',
            'status'       => 'approved',
            'validated_by' => $adminUser->id,
            'remarks'      => 'Final design approved by customer. Production can proceed.',
            'validated_at' => now(),
            'created_by'   => $adminUser->id,
        ]);

        // ════════════════════════════════════════════
        // PHASE 7 — TASKS & PRODUCTION
        // ════════════════════════════════════════════

        // 45. Seed Tasks
        $task1 = \App\Models\Task::create([
            'order_id'           => $order1->id,
            'department_id'      => $deptDesign->id,
            'assigned_to'        => $adminUser->id,
            'task_number'        => 'TSK-001',
            'title'              => 'Prepare Production Files for Main Entrance Fascia Sign',
            'description'        => 'Generate print-ready AI files, set cut guides, and send to RIP station.',
            'priority'           => 'high',
            'planned_start'      => now()->addDay(),
            'planned_end'        => now()->addDays(3),
            'planned_time_hours' => 8.0,
            'status'             => 'pending',
            'created_by'         => $adminUser->id,
        ]);

        $task2 = \App\Models\Task::create([
            'order_id'           => $order1->id,
            'department_id'      => $deptDesign->id,
            'assigned_to'        => $adminUser->id,
            'task_number'        => 'TSK-002',
            'title'              => 'QC Inspection — ACM Fascia Sign',
            'description'        => 'Inspect size accuracy, surface finish, and LED lighting before packing.',
            'priority'           => 'normal',
            'planned_start'      => now()->addDays(4),
            'planned_end'        => now()->addDays(5),
            'planned_time_hours' => 4.0,
            'status'             => 'pending',
            'created_by'         => $adminUser->id,
        ]);

        // 46. Seed Task Logs (via embedded helper)
        \App\Models\TaskLog::create([
            'task_id'    => $task1->id,
            'user_id'    => $adminUser->id,
            'action'     => 'created',
            'notes'      => 'Task created and assigned to Design team.',
        ]);

        // 47. Seed Task Acceptance
        \App\Models\TaskAcceptance::create([
            'task_id'      => $task1->id,
            'user_id'      => $adminUser->id,
            'status'       => 'accepted',
            'responded_at' => now(),
        ]);

        // 48. Seed Task Proof
        \App\Models\TaskProof::create([
            'task_id'    => $task1->id,
            'file_path'  => 'task-proofs/tsk001-artwork.pdf',
            'file_type'  => 'application/pdf',
            'notes'      => 'Production-ready artwork file uploaded.',
            'created_by' => $adminUser->id,
        ]);

        // 49. Seed Task Delay
        \App\Models\TaskDelay::create([
            'task_id'          => $task2->id,
            'delay_reason'     => 'Material delivery delayed by vendor — ACM panels not arrived.',
            'escalation_level' => 1,
            'escalated_to'     => $adminUser->id,
            'created_by'       => $adminUser->id,
        ]);

        // 50. Seed Task Escalation
        \App\Models\TaskEscalation::create([
            'task_id'        => $task2->id,
            'escalated_from' => $adminUser->id,
            'escalated_to'   => $adminUser->id,
            'reason'         => 'Material delay exceeding SLA. Escalating to Production Head.',
            'level'          => 1,
            'status'         => 'open',
            'created_by'     => $adminUser->id,
        ]);

        // 51. Seed Task Bottleneck
        \App\Models\TaskBottleneck::create([
            'task_id'         => $task2->id,
            'bottleneck_type' => 'Material Unavailability',
            'description'     => 'ACM panels out of stock. Production blocked pending reorder.',
            'identified_by'   => $adminUser->id,
            'created_by'      => $adminUser->id,
        ]);

        // 52. Seed Task Verification
        \App\Models\TaskVerification::create([
            'task_id'     => $task1->id,
            'verified_by' => $adminUser->id,
            'status'      => 'approved',
            'remarks'     => 'Production files reviewed and approved by Design Head.',
            'verified_at' => now(),
            'created_by'  => $adminUser->id,
        ]);

        // 53. Seed Job Card for Production Plan
        $jobCard = \App\Models\JobCard::where('order_id', $order1->id)->first();

        // 54. Seed Production Plan
        $productionPlan1 = \App\Models\ProductionPlan::create([
            'order_id'    => $order1->id,
            'job_card_id' => $jobCard->id,
            'plan_number' => 'PP-001',
            'start_date'  => now()->addDay()->toDateString(),
            'end_date'    => now()->addDays(10)->toDateString(),
            'status'      => 'planned',
            'notes'       => 'ACM Fascia Sign production plan. 6 stages.',
            'created_by'  => $adminUser->id,
        ]);

        // 55. Seed Production Stages
        $stage1 = \App\Models\ProductionStage::create([
            'production_plan_id' => $productionPlan1->id,
            'stage_name'         => 'File Preparation',
            'sort_order'         => 1,
            'planned_start'      => now()->addDay(),
            'planned_end'        => now()->addDays(2),
            'status'             => 'pending',
            'assigned_to'        => $adminUser->id,
            'created_by'         => $adminUser->id,
        ]);

        $stage2 = \App\Models\ProductionStage::create([
            'production_plan_id' => $productionPlan1->id,
            'stage_name'         => 'ACM Cutting',
            'sort_order'         => 2,
            'planned_start'      => now()->addDays(2),
            'planned_end'        => now()->addDays(4),
            'status'             => 'pending',
            'assigned_to'        => $adminUser->id,
            'created_by'         => $adminUser->id,
        ]);

        \App\Models\ProductionStage::create([
            'production_plan_id' => $productionPlan1->id,
            'stage_name'         => 'Fabrication & LED Fitting',
            'sort_order'         => 3,
            'planned_start'      => now()->addDays(4),
            'planned_end'        => now()->addDays(7),
            'status'             => 'pending',
            'assigned_to'        => $adminUser->id,
            'created_by'         => $adminUser->id,
        ]);

        // 56. Seed Production Proof
        \App\Models\ProductionProof::create([
            'production_plan_id' => $productionPlan1->id,
            'stage_id'           => $stage1->id,
            'file_path'          => 'production-proofs/pp001-stage1-file.jpg',
            'file_type'          => 'image/jpeg',
            'notes'              => 'Production file completed and approved.',
            'uploaded_by'        => $adminUser->id,
            'created_by'         => $adminUser->id,
        ]);

        // 57. Seed Production Delay
        \App\Models\ProductionDelay::create([
            'production_plan_id' => $productionPlan1->id,
            'stage_id'           => $stage2->id,
            'delay_reason'       => 'CNC machine breakdown for 6 hours. Back in service after repair.',
            'delay_hours'        => 6.0,
            'reported_by'        => $adminUser->id,
            'created_by'         => $adminUser->id,
        ]);

        // 58. Seed Production Score
        \App\Models\ProductionScore::create([
            'production_plan_id' => $productionPlan1->id,
            'quality_score'      => 90,
            'efficiency_score'   => 78,
            'on_time_score'      => 85,
            'overall_score'      => round((90 + 78 + 85) / 3, 2),
            'scored_by'          => $adminUser->id,
            'created_by'         => $adminUser->id,
        ]);

        // 59. Seed QC Checklist
        \App\Models\QcChecklist::create([
            'production_plan_id' => $productionPlan1->id,
            'item_name'          => 'Size accuracy matches approved measurement sheet',
            'is_passed'          => 1,
            'rework_required'    => 0,
            'inspected_by'       => $adminUser->id,
            'notes'              => 'Dimensions match within ±2mm tolerance.',
            'created_by'         => $adminUser->id,
        ]);

        \App\Models\QcChecklist::create([
            'production_plan_id' => $productionPlan1->id,
            'item_name'          => 'LED illumination uniform and flicker-free',
            'is_passed'          => 1,
            'rework_required'    => 0,
            'inspected_by'       => $adminUser->id,
            'notes'              => 'All LEDs tested for 30 minutes. No flicker detected.',
            'created_by'         => $adminUser->id,
        ]);

        \App\Models\QcChecklist::create([
            'production_plan_id' => $productionPlan1->id,
            'item_name'          => 'Surface finish — no scratches or dents visible',
            'is_passed'          => 0,
            'rework_required'    => 1,
            'inspected_by'       => $adminUser->id,
            'notes'              => 'Minor scratch found on bottom-right corner. Rework required.',
            'created_by'         => $adminUser->id,
        ]);

        // 60. Seed Rework Log
        \App\Models\ReworkLog::create([
            'production_plan_id' => $productionPlan1->id,
            'stage_id'           => $stage2->id,
            'reason'             => 'Minor surface scratch on bottom-right panel edge. Re-lamination required.',
            'cost_incurred'      => 500.00,
            'time_hours'         => 2.0,
            'assigned_to'        => $adminUser->id,
            'status'             => 'pending',
            'created_by'         => $adminUser->id,
        ]);

        // ════════════════════════════════════════════
        // PHASE 8 — INVENTORY & PROCUREMENT
        // ════════════════════════════════════════════

        // 61. Seed Items
        $item1 = \App\Models\Item::create([
            'sku_code'      => 'ITM-ACM-001',
            'name'          => 'ACM Panel 3mm (Alubond)',
            'description'   => '3mm Aluminum Composite Material panel suitable for outdoor signage.',
            'category'      => 'Raw Material',
            'sub_category'  => 'Panels',
            'uom'           => 'Sqft',
            'reorder_level' => 100,
            'current_stock' => 500,
            'hsn_code'      => '76061200',
            'tax_rate'      => 18.00,
            'unit_cost'     => 120.00,
            'is_active'     => 1,
            'created_by'    => $adminUser->id,
        ]);

        $item2 = \App\Models\Item::create([
            'sku_code'      => 'ITM-LED-12V',
            'name'          => '12V LED Module (White)',
            'description'   => 'High brightness 12V LED module for channel letters.',
            'category'      => 'Electrical',
            'sub_category'  => 'Lighting',
            'uom'           => 'Pcs',
            'reorder_level' => 500,
            'current_stock' => 200,
            'hsn_code'      => '85414020',
            'tax_rate'      => 18.00,
            'unit_cost'     => 15.00,
            'is_active'     => 1,
            'created_by'    => $adminUser->id,
        ]);

        // 62. Seed Stock Alerts
        \App\Models\StockAlert::create([
            'item_id'       => $item2->id,
            'current_qty'   => 200,
            'reorder_level' => 500,
            'alert_type'    => 'low_stock',
            'is_resolved'   => 0,
            'created_by'    => $adminUser->id,
        ]);

        // 63. Seed Vendors
        $vendor1 = \App\Models\Vendor::create([
            'name'           => 'Global Sign Supplies',
            'contact_person' => 'Rajesh Kumar',
            'phone'          => '9988776655',
            'email'          => 'sales@globalsign.in',
            'gstin'          => '27AAACG1234H1Z5',
            'address'        => 'Industrial Estate, Andheri East',
            'city'           => 'Mumbai',
            'state'          => 'Maharashtra',
            'credit_days'    => 30,
            'status'         => 'active',
            'created_by'     => $adminUser->id,
        ]);

        // 64. Seed Vendor Quotations
        \App\Models\VendorQuotation::create([
            'vendor_id'    => $vendor1->id,
            'item_id'      => $item1->id,
            'quoted_price' => 115.00,
            'min_qty'      => 500,
            'lead_days'    => 3,
            'valid_till'   => now()->addDays(30)->toDateString(),
            'notes'        => 'Special discount for bulk purchase.',
            'created_by'   => $adminUser->id,
        ]);

        // 65. Seed Purchase Requests
        $pr1 = \App\Models\PurchaseRequest::create([
            'order_id'      => $order1->id,
            'department_id' => $deptDesign->id,
            'requested_by'  => $adminUser->id,
            'pr_number'     => 'PR-0001',
            'required_by'   => now()->addDays(5)->toDateString(),
            'status'        => 'approved',
            'approved_by'   => $adminUser->id,
            'approved_at'   => now(),
            'remarks'       => 'Required for Order ORD-001 production.',
            'created_by'    => $adminUser->id,
        ]);

        // 66. Seed PR Items
        \App\Models\PurchaseRequestItem::create([
            'purchase_request_id' => $pr1->id,
            'item_id'             => $item1->id,
            'qty'                 => 200,
            'notes'               => 'ACM panels for fascia.',
            'created_by'          => $adminUser->id,
        ]);

        // 67. Seed Purchase Orders
        $po1 = \App\Models\PurchaseOrder::create([
            'vendor_id'           => $vendor1->id,
            'purchase_request_id' => $pr1->id,
            'po_number'           => 'PO-26001',
            'total_amount'        => 24000.00,
            'tax_amount'          => 4320.00,
            'grand_total'         => 28320.00,
            'expected_delivery'   => now()->addDays(3)->toDateString(),
            'status'              => 'approved',
            'approved_by'         => $adminUser->id,
            'approved_at'         => now(),
            'created_by'          => $adminUser->id,
        ]);

        // 68. Seed PO Items
        \App\Models\PurchaseOrderItem::create([
            'po_id'        => $po1->id,
            'item_id'      => $item1->id,
            'qty'          => 200,
            'rate'         => 120.00,
            'tax_rate'     => 18.00,
            'total'        => 24000.00,
            'qty_received' => 0,
            'created_by'   => $adminUser->id,
        ]);

        // 69. Seed Inventory Transactions
        \App\Models\InventoryTransaction::create([
            'item_id'        => $item1->id,
            'type'           => 'in',
            'qty'            => 500,
            'balance_qty'    => 500,
            'unit_cost'      => 120.00,
            'total_cost'     => 60000.00,
            'reference_type' => 'Opening Stock',
            'date'           => now(),
            'notes'          => 'Initial stock entry',
            'created_by'     => $adminUser->id,
        ]);

        // 70. Seed Material Kitting
        $kitting1 = \App\Models\MaterialKitting::create([
            'order_id'           => $order1->id,
            'production_plan_id' => $productionPlan1->id,
            'kit_number'         => 'KIT-001',
            'status'             => 'issued',
            'issued_at'          => now(),
            'issued_by'          => $adminUser->id,
            'created_by'         => $adminUser->id,
        ]);

        // 71. Seed Kitting Items
        \App\Models\MaterialKittingItem::create([
            'kitting_id'   => $kitting1->id,
            'item_id'      => $item1->id,
            'required_qty' => 50,
            'issued_qty'   => 50,
            'created_by'   => $adminUser->id,
        ]);

        // 72. Seed Material Consumption
        \App\Models\MaterialConsumption::create([
            'order_id'           => $order1->id,
            'production_plan_id' => $productionPlan1->id,
            'item_id'            => $item1->id,
            'consumed_qty'       => 48,
            'wastage_qty'        => 2,
            'stage_id'           => $stage2->id,
            'consumed_by'        => $adminUser->id,
            'consumed_at'        => now(),
            'created_by'         => $adminUser->id,
        ]);

        // 73. Seed Vendor Payments
        \App\Models\VendorPayment::create([
            'vendor_id'       => $vendor1->id,
            'po_id'           => $po1->id,
            'payment_mode'    => 'Bank Transfer',
            'amount'          => 14160.00, // 50% advance
            'transaction_ref' => 'NEFT-1234567890',
            'payment_date'    => now()->toDateString(),
            'status'          => 'approved',
            'approved_by'     => $adminUser->id,
            'approved_at'     => now(),
            'created_by'      => $adminUser->id,
        ]);

        // ────────────────────────────────────────────────────
        // PHASE 9 — DISPATCH & INSTALLATION
        // ────────────────────────────────────────────────────

        // 74. Seed Dispatch
        $dispatch1 = \App\Models\Dispatch::create([
            'order_id' => $order1->id,
            'dispatch_number' => 'DSP-' . date('Ymd') . '-001',
            'vehicle_number' => 'KA-01-AB-1234',
            'driver_name' => 'Raju Driver',
            'driver_phone' => '9876543210',
            'transport_company' => 'Safe Express',
            'status' => 'dispatched',
            'created_by' => $adminUser->id,
            'dispatched_at' => now(),
        ]);

        // 75. Seed Packing Checklist
        \App\Models\PackingChecklist::create([
            'dispatch_id' => $dispatch1->id,
            'item_name' => 'Main Sign Board',
            'is_packed' => true,
            'created_by' => $adminUser->id,
        ]);

        // 76. Seed Dispatch Item
        $orderItem1 = \App\Models\OrderItem::where('order_id', $order1->id)->first();
        if ($orderItem1) {
            \App\Models\DispatchItem::create([
                'dispatch_id' => $dispatch1->id,
                'order_item_id' => $orderItem1->id,
                'qty_dispatched' => 1,
                'package_number' => 'PKG-001',
                'created_by' => $adminUser->id,
            ]);
        }

        // 77. Seed Dispatch Approval
        \App\Models\DispatchApproval::create([
            'dispatch_id' => $dispatch1->id,
            'approved_by' => $adminUser->id,
            'status' => 'approved',
            'created_by' => $adminUser->id,
            'approved_at' => now(),
        ]);

        // 78. Seed Dispatch Proof
        \App\Models\DispatchProof::create([
            'dispatch_id' => $dispatch1->id,
            'proof_type' => 'Vehicle Photo',
            'file_path' => '/uploads/proofs/dispatch.jpg',
            'created_by' => $adminUser->id,
        ]);

        // 79. Seed Vehicle Tracking Log
        \App\Models\VehicleTrackingLog::create([
            'dispatch_id' => $dispatch1->id,
            'latitude' => 12.9716,
            'longitude' => 77.5946,
            'location_name' => 'Bangalore Checkpoint',
            'tracked_at' => now(),
        ]);

        // 80. Seed Delivery Confirmation
        \App\Models\DeliveryConfirmation::create([
            'dispatch_id' => $dispatch1->id,
            'received_by' => 'John Receiver',
            'otp_verified' => true,
            'confirmed_at' => now(),
            'created_by' => $adminUser->id,
        ]);

        // 81. Seed Installation
        $installation1 = \App\Models\Installation::create([
            'order_id' => $order1->id,
            'dispatch_id' => $dispatch1->id,
            'assigned_to' => $adminUser->id,
            'installation_number' => 'INS-' . date('Ymd') . '-001',
            'status' => 'completed',
            'completion_percentage' => 100,
            'created_by' => $adminUser->id,
            'actual_date' => now()->toDateString(),
        ]);

        // 82. Seed Installation Material Confirmation
        \App\Models\InstallationMaterialConfirmation::create([
            'installation_id' => $installation1->id,
            'item_id' => $item1->id,
            'expected_qty' => 5,
            'received_qty' => 5,
            'is_confirmed' => true,
            'created_by' => $adminUser->id,
        ]);

        // 83. Seed Installation GPS Log
        \App\Models\InstallationGpsLog::create([
            'installation_id' => $installation1->id,
            'user_id' => $adminUser->id,
            'latitude' => 12.9716,
            'longitude' => 77.5946,
            'log_type' => 'check-in',
            'tracked_at' => now(),
        ]);

        // 84. Seed Installation Photo
        \App\Models\InstallationPhoto::create([
            'installation_id' => $installation1->id,
            'type' => 'completed',
            'file_path' => '/uploads/installations/photo.jpg',
            'created_by' => $adminUser->id,
        ]);

        // 85. Seed Installation Correction
        \App\Models\InstallationCorrection::create([
            'installation_id' => $installation1->id,
            'issue_description' => 'Minor scratch on edge',
            'corrective_action' => 'Touched up with paint',
            'status' => 'resolved',
            'created_by' => $adminUser->id,
            'resolved_at' => now(),
        ]);

        // 86. Seed Installation Signoff
        \App\Models\InstallationSignoff::create([
            'installation_id' => $installation1->id,
            'customer_name' => 'John Customer',
            'satisfaction_score' => 5,
            'created_by' => $adminUser->id,
            'signed_at' => now(),
        ]);

        // 87. Seed Installation Score
        \App\Models\InstallationScore::create([
            'installation_id' => $installation1->id,
            'quality_score' => 5,
            'timeliness_score' => 4,
            'customer_satisfaction' => 5,
            'overall_score' => 4.67,
            'created_by' => $adminUser->id,
        ]);
        // ----------------------------------------------------
        // PHASE 10: INVOICING, FINANCIAL CONTROL & SUPPORT
        // ----------------------------------------------------

        // 88. Seed Warranty Card
        $warrantyCard = \App\Models\WarrantyCard::create([
            'customer_id' => $customer1->id,
            'order_id' => $order1->id,
            'warranty_number' => 'WAR-1001',
            'valid_from' => now(),
            'valid_till' => now()->addYear(),
            'terms' => 'Standard 1 year warranty',
            'issued_by' => $adminUser->id,
            'created_by' => $adminUser->id,
        ]);

        // 89. Seed Service Ticket
        $ticket = \App\Models\ServiceTicket::create([
            'customer_id' => $customer1->id,
            'order_id' => $order1->id,
            'ticket_number' => 'TKT-2001',
            'issue_description' => 'Light not turning on',
            'issue_type' => 'electrical',
            'priority' => 'high',
            'is_warranty' => true,
            'warranty_card_id' => $warrantyCard->id,
            'status' => 'open',
            'created_by' => $adminUser->id,
        ]);

        // 90. Seed Service Assignment
        \App\Models\ServiceAssignment::create([
            'ticket_id' => $ticket->id,
            'technician_id' => $adminUser->id,
            'visit_date' => now()->addDay(),
            'visit_time' => '10:00:00',
            'status' => 'scheduled',
            'created_by' => $adminUser->id,
        ]);

        // 91. Seed Invoice
        $invoice = \App\Models\Invoice::create([
            'order_id' => $order1->id,
            'customer_id' => $customer1->id,
            'branch_id' => $branchMain->id,
            'invoice_number' => 'INV-3001',
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'sub_total' => 50000,
            'tax_cgst' => 4500,
            'tax_sgst' => 4500,
            'total' => 59000,
            'balance_due' => 59000,
            'status' => 'unpaid',
            'created_by' => $adminUser->id,
        ]);

        // 92. Seed Invoice Item
        \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => 'Main Signage Panel',
            'qty' => 1,
            'rate' => 50000,
            'amount' => 50000,
            'created_by' => $adminUser->id,
        ]);

        // 93. Seed Receipt
        $receipt = \App\Models\Receipt::create([
            'invoice_id' => $invoice->id,
            'customer_id' => $customer1->id,
            'receipt_number' => 'RCT-4001',
            'amount_received' => 20000,
            'payment_mode' => 'bank_transfer',
            'date' => now(),
            'created_by' => $adminUser->id,
        ]);

        // Update invoice balance manually for seeder
        $invoice->update([
            'amount_paid' => 20000,
            'balance_due' => 39000,
            'status' => 'partial'
        ]);

        // 94. Seed Expense
        \App\Models\Expense::create([
            'branch_id' => $branchMain->id,
            'expense_number' => 'EXP-5001',
            'category' => 'Travel',
            'description' => 'Site visit travel expense',
            'amount' => 500,
            'total_amount' => 500,
            'status' => 'approved',
            'approved_by' => $adminUser->id,
            'created_by' => $adminUser->id,
        ]);

        // 95. Seed Customer Ledger
        \App\Models\CustomerLedger::create([
            'customer_id' => $customer1->id,
            'type' => 'debit',
            'reference_type' => 'invoice',
            'reference_id' => $invoice->id,
            'amount' => 59000,
            'description' => 'Invoice ' . $invoice->invoice_number,
            'transaction_date' => now(),
            'created_by' => $adminUser->id,
        ]);

        \App\Models\CustomerLedger::create([
            'customer_id' => $customer1->id,
            'type' => 'credit',
            'reference_type' => 'receipt',
            'reference_id' => $receipt->id,
            'amount' => 20000,
            'description' => 'Receipt ' . $receipt->receipt_number,
            'transaction_date' => now(),
            'created_by' => $adminUser->id,
        ]);
        // ────────────────────────────────────────────────────
        // PHASE 11 — HR, PAYROLL, DAILY AUDIT & COMMS
        // ────────────────────────────────────────────────────

        // 96. Seed Attendance
        $attendance = \App\Models\Attendance::create([
            'user_id' => $adminUser->id,
            'date' => now()->toDateString(),
            'punch_in' => now()->startOfDay()->addHours(9),
            'status' => 'present',
            'work_hours' => 8,
            'created_by' => $adminUser->id,
        ]);

        // 97. Seed Recruitment
        $recruitment = \App\Models\Recruitment::create([
            'department_id' => $deptSales->id,
            'position' => 'Senior Sales Executive',
            'budget_min' => 40000,
            'budget_max' => 60000,
            'status' => 'open',
            'created_by' => $adminUser->id,
        ]);

        // 98. Seed Help Ticket
        \App\Models\HelpTicket::create([
            'raised_by' => $adminUser->id,
            'department_id' => $deptSales->id,
            'ticket_number' => 'HT-1001',
            'issue' => 'Cannot access the CRM dashboard',
            'priority' => 'high',
            'status' => 'open',
            'created_by' => $adminUser->id,
        ]);
        
        // 99. Seed Notification
        \App\Models\Notification::create([
            'user_id' => $adminUser->id,
            'title' => 'System Update',
            'message' => 'HR module has been activated.',
            'type' => 'system',
        ]);
    }
}

