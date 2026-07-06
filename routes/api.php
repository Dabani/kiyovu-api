<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\Membership\HonoraryNominationController;
use App\Http\Controllers\Api\Membership\HonoraryNominationDossierController;
use App\Http\Controllers\Api\Membership\MemberController;
use App\Http\Controllers\Api\Membership\MemberFeeWaiverRequestController;
use App\Http\Controllers\Api\Membership\MemberInactiveStatusRequestController;
use App\Http\Controllers\Api\Membership\MemberInformationRequestController;
use App\Http\Controllers\Api\Membership\MemberReinstatementRequestController;
use App\Http\Controllers\Api\Membership\MemberResignationController;
use App\Http\Controllers\Api\Hr\HrAppointmentRecommendationController;
use App\Http\Controllers\Api\Hr\HrBackgroundCheckController;
use App\Http\Controllers\Api\Hr\HrConflictOfInterestDeclarationController;
use App\Http\Controllers\Api\Hr\HrEmploymentContractController;
use App\Http\Controllers\Api\Hr\HrGiftDeclarationController;
use App\Http\Controllers\Api\Hr\HrInterviewScoreController;
use App\Http\Controllers\Api\Hr\RecruitmentCandidateController;
use App\Http\Controllers\Api\Elections\ElectionDisputeController;
use App\Http\Controllers\Api\Elections\ElectionHandoverReportController;
use App\Http\Controllers\Api\Elections\ElectionNominationController;
use App\Http\Controllers\Api\Elections\ElectionResultsCertificationController;
use App\Http\Controllers\Api\Elections\ElectionTallySheetController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/lookups/{key}', [LookupController::class, 'index']);

    // ---- Bundle 1: Membership & Honorary (MEM-001..007, HON-001/002) ----
    Route::get('/members/report', [MemberController::class, 'report']);
    Route::apiResource('members', MemberController::class);

    Route::get('/member-information-requests/report', [MemberInformationRequestController::class, 'report']);
    Route::apiResource('member-information-requests', MemberInformationRequestController::class)
        ->except(['show']);

    Route::get('/member-inactive-status-requests/report', [MemberInactiveStatusRequestController::class, 'report']);
    Route::apiResource('member-inactive-status-requests', MemberInactiveStatusRequestController::class)
        ->except(['show']);

    Route::get('/member-fee-waiver-requests/report', [MemberFeeWaiverRequestController::class, 'report']);
    Route::apiResource('member-fee-waiver-requests', MemberFeeWaiverRequestController::class)
        ->except(['show']);

    Route::get('/member-resignations/report', [MemberResignationController::class, 'report']);
    Route::apiResource('member-resignations', MemberResignationController::class)
        ->except(['show']);

    Route::get('/member-reinstatement-requests/report', [MemberReinstatementRequestController::class, 'report']);
    Route::apiResource('member-reinstatement-requests', MemberReinstatementRequestController::class)
        ->except(['show']);

    Route::get('/honorary-nominations/report', [HonoraryNominationController::class, 'report']);
    Route::apiResource('honorary-nominations', HonoraryNominationController::class)
        ->except(['show']);

    Route::get('/honorary-nomination-dossiers/report', [HonoraryNominationDossierController::class, 'report']);
    Route::apiResource('honorary-nomination-dossiers', HonoraryNominationDossierController::class)
        ->except(['show']);

    // ---- Bundle 2: HR & Recruitment (HR-001..007) ----
    Route::get('/recruitment-candidates/report', [RecruitmentCandidateController::class, 'report']);
    Route::apiResource('recruitment-candidates', RecruitmentCandidateController::class)->except(['show']);

    Route::get('/hr-employment-contracts/report', [HrEmploymentContractController::class, 'report']);
    Route::apiResource('hr-employment-contracts', HrEmploymentContractController::class)->except(['show']);

    Route::get('/hr-background-checks/report', [HrBackgroundCheckController::class, 'report']);
    Route::apiResource('hr-background-checks', HrBackgroundCheckController::class)->except(['show']);

    Route::get('/hr-conflict-of-interest-declarations/report', [HrConflictOfInterestDeclarationController::class, 'report']);
    Route::apiResource('hr-conflict-of-interest-declarations', HrConflictOfInterestDeclarationController::class)->except(['show']);

    Route::get('/hr-gift-declarations/report', [HrGiftDeclarationController::class, 'report']);
    Route::apiResource('hr-gift-declarations', HrGiftDeclarationController::class)->except(['show']);

    Route::get('/hr-interview-scores/report', [HrInterviewScoreController::class, 'report']);
    Route::apiResource('hr-interview-scores', HrInterviewScoreController::class)->except(['show']);

    Route::get('/hr-appointment-recommendations/report', [HrAppointmentRecommendationController::class, 'report']);
    Route::apiResource('hr-appointment-recommendations', HrAppointmentRecommendationController::class)->except(['show']);

    // ---- Bundle 3: Elections (ELEC-001..005) ----
    Route::get('/election-nominations/report', [ElectionNominationController::class, 'report']);
    Route::apiResource('election-nominations', ElectionNominationController::class)->except(['show']);

    Route::get('/election-tally-sheets/report', [ElectionTallySheetController::class, 'report']);
    Route::apiResource('election-tally-sheets', ElectionTallySheetController::class)->except(['show']);

    Route::get('/election-results-certifications/report', [ElectionResultsCertificationController::class, 'report']);
    Route::apiResource('election-results-certifications', ElectionResultsCertificationController::class)->except(['show']);

    Route::get('/election-handover-reports/report', [ElectionHandoverReportController::class, 'report']);
    Route::apiResource('election-handover-reports', ElectionHandoverReportController::class)->except(['show']);

    Route::get('/election-disputes/report', [ElectionDisputeController::class, 'report']);
    Route::apiResource('election-disputes', ElectionDisputeController::class)->except(['show']);

    // Module bundle route groups will be registered here, one per delivery:
    // Bundle 4 (Disciplinary & Legal), Bundle 5 (Financial, Procurement & Asset), ...
});
