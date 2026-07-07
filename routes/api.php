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
use App\Http\Controllers\Api\FanClubs\FanClubController;
use App\Http\Controllers\Api\FanClubs\FanClubAnnualReportController;
use App\Http\Controllers\Api\FanClubs\FanClubDeregistrationWarningController;
use App\Http\Controllers\Api\FanClubs\FanClubFinancialSummaryController;
use App\Http\Controllers\Api\FanClubs\FanClubMembershipRegisterController;
use App\Http\Controllers\Api\FanClubs\FanClubPaymentConfirmationController;
use App\Http\Controllers\Api\FanClubs\FanIncidentReportController;
use App\Http\Controllers\Api\Players\AntiDopingDeclarationController;
use App\Http\Controllers\Api\Players\CodeOfConductAcknowledgementController;
use App\Http\Controllers\Api\Operations\CommissionAnnualWorkPlanController;
use App\Http\Controllers\Api\Operations\CommissionKpiReportController;
use App\Http\Controllers\Api\Operations\GuestRegisterController;
use App\Http\Controllers\Api\Operations\SecurityIncidentReportController;
use App\Http\Controllers\Api\Players\ParentalConsentFormController;
use App\Http\Controllers\Api\Players\PlayerController;
use App\Http\Controllers\Api\Players\PlayerContractController;
use App\Http\Controllers\Api\Players\PlayerLoanAgreementController;
use App\Http\Controllers\Api\Players\SafeguardingConcernReportController;
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

    // ---- Bundle 6: Fan Clubs (FAN-001..008) ----
    Route::get('/fan-clubs/report', [FanClubController::class, 'report']);
    Route::apiResource('fan-clubs', FanClubController::class)->except(['show']);

    Route::get('/fan-club-annual-reports/report', [FanClubAnnualReportController::class, 'report']);
    Route::apiResource('fan-club-annual-reports', FanClubAnnualReportController::class)->except(['show']);

    Route::get('/fan-club-financial-summaries/report', [FanClubFinancialSummaryController::class, 'report']);
    Route::apiResource('fan-club-financial-summaries', FanClubFinancialSummaryController::class)->except(['show']);

    Route::get('/fan-incident-reports/report', [FanIncidentReportController::class, 'report']);
    Route::apiResource('fan-incident-reports', FanIncidentReportController::class)->except(['show']);

    Route::get('/fan-club-deregistration-warnings/report', [FanClubDeregistrationWarningController::class, 'report']);
    Route::apiResource('fan-club-deregistration-warnings', FanClubDeregistrationWarningController::class)->except(['show']);

    Route::get('/fan-club-payment-confirmations/report', [FanClubPaymentConfirmationController::class, 'report']);
    Route::apiResource('fan-club-payment-confirmations', FanClubPaymentConfirmationController::class)->except(['show']);

    Route::get('/fan-club-membership-registers/report', [FanClubMembershipRegisterController::class, 'report']);
    Route::apiResource('fan-club-membership-registers', FanClubMembershipRegisterController::class)->except(['show']);

    // ---- Bundle 7: Players & Safeguarding (PLAYER-001..004, SAFE-001..003) ----
    Route::get('/players/report', [PlayerController::class, 'report']);
    Route::apiResource('players', PlayerController::class)->except(['show']);

    Route::get('/player-contracts/report', [PlayerContractController::class, 'report']);
    Route::apiResource('player-contracts', PlayerContractController::class)->except(['show']);

    Route::get('/player-loan-agreements/report', [PlayerLoanAgreementController::class, 'report']);
    Route::apiResource('player-loan-agreements', PlayerLoanAgreementController::class)->except(['show']);

    Route::get('/anti-doping-declarations/report', [AntiDopingDeclarationController::class, 'report']);
    Route::apiResource('anti-doping-declarations', AntiDopingDeclarationController::class)->except(['show']);

    Route::get('/safeguarding-concern-reports/report', [SafeguardingConcernReportController::class, 'report']);
    Route::apiResource('safeguarding-concern-reports', SafeguardingConcernReportController::class)->except(['show']);

    Route::get('/parental-consent-forms/report', [ParentalConsentFormController::class, 'report']);
    Route::apiResource('parental-consent-forms', ParentalConsentFormController::class)->except(['show']);

    Route::get('/code-of-conduct-acknowledgements/report', [CodeOfConductAcknowledgementController::class, 'report']);
    Route::apiResource('code-of-conduct-acknowledgements', CodeOfConductAcknowledgementController::class)->except(['show']);

    // ---- Bundle 8: Operations, Security & Commissions (OPS-001, SEC-001, COMM-001/002) — final bundle ----
    Route::get('/guest-registers/report', [GuestRegisterController::class, 'report']);
    Route::apiResource('guest-registers', GuestRegisterController::class)->except(['show']);

    Route::get('/security-incident-reports/report', [SecurityIncidentReportController::class, 'report']);
    Route::apiResource('security-incident-reports', SecurityIncidentReportController::class)->except(['show']);

    Route::get('/commission-annual-work-plans/report', [CommissionAnnualWorkPlanController::class, 'report']);
    Route::apiResource('commission-annual-work-plans', CommissionAnnualWorkPlanController::class)->except(['show']);

    Route::get('/commission-kpi-reports/report', [CommissionKpiReportController::class, 'report']);
    Route::apiResource('commission-kpi-reports', CommissionKpiReportController::class)->except(['show']);
});
