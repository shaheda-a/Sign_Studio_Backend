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
    }
}
