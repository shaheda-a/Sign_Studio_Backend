<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StageMasterController;
use App\Http\Controllers\StatusMasterController;
use App\Http\Controllers\ChecklistMasterController;
use App\Http\Controllers\ApprovalRuleController;
use App\Http\Controllers\EscalationRuleController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\BackupLogController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerContactController;
use App\Http\Controllers\CustomerSiteController;
use App\Http\Controllers\CustomerReferralController;
use App\Http\Controllers\ArchitectController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\PipelineStageController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadActivityController;
use App\Http\Controllers\LeadFollowupController;
use App\Http\Controllers\LeadScoreController;
use App\Http\Controllers\LeadValidationController;
use App\Http\Controllers\CallLogController;
use App\Http\Controllers\BookingTokenController;
use App\Http\Controllers\SiteVisitController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DesignRevisionController;
use App\Http\Controllers\DesignApprovalController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\JobCardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Auth Route
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Authenticated User Routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('profile', [UserController::class, 'profile']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('change-password', [UserController::class, 'changePassword']);

    // Branches
    Route::post('branches/{branch}/restore', [BranchController::class, 'restore']);
    Route::apiResource('branches', BranchController::class);

    // Departments
    Route::post('departments/{department}/restore', [DepartmentController::class, 'restore']);
    Route::apiResource('departments', DepartmentController::class);

    // Users Management
    Route::post('users/{user}/restore', [UserController::class, 'restore']);
    Route::apiResource('users', UserController::class);

    // Stage Masters
    Route::post('stage-masters/{stage_master}/restore', [StageMasterController::class, 'restore']);
    Route::apiResource('stage-masters', StageMasterController::class);

    // Status Masters
    Route::post('status-masters/{status_master}/restore', [StatusMasterController::class, 'restore']);
    Route::apiResource('status-masters', StatusMasterController::class);

    // Checklist Masters
    Route::post('checklist-masters/{checklist_master}/restore', [ChecklistMasterController::class, 'restore']);
    Route::apiResource('checklist-masters', ChecklistMasterController::class);

    // Approval Rules
    Route::post('approval-rules/{approval_rule}/restore', [ApprovalRuleController::class, 'restore']);
    Route::apiResource('approval-rules', ApprovalRuleController::class);

    // Escalation Rules
    Route::post('escalation-rules/{escalation_rule}/restore', [EscalationRuleController::class, 'restore']);
    Route::apiResource('escalation-rules', EscalationRuleController::class);

    // Templates
    Route::post('templates/{template}/restore', [TemplateController::class, 'restore']);
    Route::apiResource('templates', TemplateController::class);

    // Backup Logs
    Route::apiResource('backup-logs', BackupLogController::class)->only(['index', 'store', 'show']);

    // API Keys
    Route::post('api-keys/{api_key}/restore', [ApiKeyController::class, 'restore']);
    Route::apiResource('api-keys', ApiKeyController::class);

    // Logs & History
    Route::apiResource('audit-logs', AuditLogController::class)->only(['index']);
    Route::apiResource('login-histories', LoginHistoryController::class)->only(['index']);

    // Customers
    Route::post('customers/{customer}/restore', [CustomerController::class, 'restore']);
    Route::apiResource('customers', CustomerController::class);

    // Customer Contacts
    Route::post('customer-contacts/{customer_contact}/restore', [CustomerContactController::class, 'restore']);
    Route::apiResource('customer-contacts', CustomerContactController::class);

    // Customer Sites
    Route::post('customer-sites/{customer_site}/restore', [CustomerSiteController::class, 'restore']);
    Route::apiResource('customer-sites', CustomerSiteController::class);

    // Customer Referrals
    Route::post('customer-referrals/{customer_referral}/restore', [CustomerReferralController::class, 'restore']);
    Route::apiResource('customer-referrals', CustomerReferralController::class);

    // Architects
    Route::post('architects/{architect}/restore', [ArchitectController::class, 'restore']);
    Route::apiResource('architects', ArchitectController::class);

    // Contractors
    Route::post('contractors/{contractor}/restore', [ContractorController::class, 'restore']);
    Route::apiResource('contractors', ContractorController::class);

    // Lead Sources
    Route::post('lead-sources/{lead_source}/restore', [LeadSourceController::class, 'restore']);
    Route::apiResource('lead-sources', LeadSourceController::class);

    // Pipeline Stages
    Route::post('pipeline-stages/{pipeline_stage}/restore', [PipelineStageController::class, 'restore']);
    Route::apiResource('pipeline-stages', PipelineStageController::class);

    // Leads
    Route::post('leads/{lead}/restore', [LeadController::class, 'restore']);
    Route::post('leads/{lead}/assign', [LeadController::class, 'assign']);
    Route::post('leads/{lead}/transition-stage', [LeadController::class, 'transitionStage']);
    Route::post('leads/{lead}/transition-status', [LeadController::class, 'transitionStatus']);
    Route::apiResource('leads', LeadController::class);

    // Lead Activities
    Route::post('lead-activities/{lead_activity}/restore', [LeadActivityController::class, 'restore']);
    Route::apiResource('lead-activities', LeadActivityController::class);

    // Lead Followups
    Route::post('lead-followups/{lead_followup}/restore', [LeadFollowupController::class, 'restore']);
    Route::apiResource('lead-followups', LeadFollowupController::class);

    // Lead Scores
    Route::apiResource('lead-scores', LeadScoreController::class)->only(['index', 'store', 'show', 'destroy']);

    // Lead Validations
    Route::apiResource('lead-validations', LeadValidationController::class)->only(['index', 'store', 'show', 'destroy']);

    // Call Logs
    Route::apiResource('call-logs', CallLogController::class)->only(['index', 'store', 'show', 'destroy']);

    // Booking Tokens
    Route::post('booking-tokens/{booking_token}/restore', [BookingTokenController::class, 'restore']);
    Route::apiResource('booking-tokens', BookingTokenController::class);

    // Site Visits
    Route::post('site-visits/{site_visit}/restore', [SiteVisitController::class, 'restore']);
    Route::apiResource('site-visits', SiteVisitController::class);

    // Designs
    Route::post('designs/{design}/restore', [DesignController::class, 'restore']);
    Route::post('designs/{design}/lock', [DesignController::class, 'lock']);
    Route::apiResource('designs', DesignController::class);

    // Design Revisions
    Route::post('design-revisions/{design_revision}/restore', [DesignRevisionController::class, 'restore']);
    Route::apiResource('design-revisions', DesignRevisionController::class);

    // Design Approvals
    Route::apiResource('design-approvals', DesignApprovalController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    // Quotations
    Route::post('quotations/{quotation}/restore', [QuotationController::class, 'restore']);
    Route::post('quotations/{quotation}/recalculate', [QuotationController::class, 'recalculate']);
    Route::post('quotations/{quotation}/new-version', [QuotationController::class, 'newVersion']);
    Route::apiResource('quotations', QuotationController::class);

    // Quotation Items
    Route::apiResource('quotation-items', QuotationItemController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    // Orders
    Route::post('orders/{order}/restore', [OrderController::class, 'restore']);
    Route::post('orders/{order}/lock-commercial', [OrderController::class, 'lockCommercial']);
    Route::apiResource('orders', OrderController::class);

    // Job Cards
    Route::post('job-cards/{job_card}/restore', [JobCardController::class, 'restore']);
    Route::post('job-cards/{job_card}/lock-scope', [JobCardController::class, 'lockScope']);
    Route::apiResource('job-cards', JobCardController::class);
});
