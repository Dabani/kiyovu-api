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
use App\Http\Controllers\Api\Disciplinary\DisciplinaryCaseController;
use App\Http\Controllers\Api\Disciplinary\DisciplinaryDecisionController;
use App\Http\Controllers\Api\Disciplinary\DisciplinaryNoticeController;
use App\Http\Controllers\Api\Disciplinary\LegalCaseRegisterController;
use App\Http\Controllers\Api\Disciplinary\LegalMatterIntakeController;
use App\Http\Controllers\Api\Disciplinary\WhistleblowerReportController;
use App\Http\Controllers\Api\Financial\AssetHandoverController;
use App\Http\Controllers\Api\Financial\AssetRegisterController;
use App\Http\Controllers\Api\Financial\PaymentAuthorizationController;
use App\Http\Controllers\Api\Financial\PettyCashVoucherController;
use App\Http\Controllers\Api\Financial\ProcurementRfqController;
use App\Http\Controllers\Api\Financial\ProcurementTenderController;
use App\Http\Controllers\Api\Financial\WrittenContractController;
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

    // ---- Bundle 4: Disciplinary & Legal (DISC-001/002/003/005, LEG-001/002) ----
    Route::get('/disciplinary-cases/report', [DisciplinaryCaseController::class, 'report']);
    Route::apiResource('disciplinary-cases', DisciplinaryCaseController::class)->except(['show']);

    Route::get('/disciplinary-decisions/report', [DisciplinaryDecisionController::class, 'report']);
    Route::apiResource('disciplinary-decisions', DisciplinaryDecisionController::class)->except(['show']);

    Route::get('/disciplinary-notices/report', [DisciplinaryNoticeController::class, 'report']);
    Route::apiResource('disciplinary-notices', DisciplinaryNoticeController::class)->except(['show']);

    Route::get('/whistleblower-reports/report', [WhistleblowerReportController::class, 'report']);
    Route::apiResource('whistleblower-reports', WhistleblowerReportController::class)->except(['show']);

    Route::get('/legal-matter-intakes/report', [LegalMatterIntakeController::class, 'report']);
    Route::apiResource('legal-matter-intakes', LegalMatterIntakeController::class)->except(['show']);

    Route::get('/legal-case-register/report', [LegalCaseRegisterController::class, 'report']);
    Route::apiResource('legal-case-register', LegalCaseRegisterController::class)->except(['show']);

    // ---- Bundle 5: Financial, Procurement & Asset (FIN-001/003, PROC-002/003/004, ASSET-001/003) ----
    Route::get('/payment-authorizations/report', [PaymentAuthorizationController::class, 'report']);
    Route::apiResource('payment-authorizations', PaymentAuthorizationController::class)->except(['show']);

    Route::get('/petty-cash-vouchers/report', [PettyCashVoucherController::class, 'report']);
    Route::apiResource('petty-cash-vouchers', PettyCashVoucherController::class)->except(['show']);

    Route::get('/procurement-rfqs/report', [ProcurementRfqController::class, 'report']);
    Route::apiResource('procurement-rfqs', ProcurementRfqController::class)->except(['show']);

    Route::get('/procurement-tenders/report', [ProcurementTenderController::class, 'report']);
    Route::apiResource('procurement-tenders', ProcurementTenderController::class)->except(['show']);

    Route::get('/written-contracts/report', [WrittenContractController::class, 'report']);
    Route::apiResource('written-contracts', WrittenContractController::class)->except(['show']);

    Route::get('/asset-register/report', [AssetRegisterController::class, 'report']);
    Route::apiResource('asset-register', AssetRegisterController::class)->except(['show']);

    Route::get('/asset-handovers/report', [AssetHandoverController::class, 'report']);
    Route::apiResource('asset-handovers', AssetHandoverController::class)->except(['show']);

    // Module bundle route groups will be registered here, one per delivery:
    // Bundle 6 (Fan Clubs), Bundle 7 (Players & Safeguarding), Bundle 8 (Operations, Security & Commissions)
});
