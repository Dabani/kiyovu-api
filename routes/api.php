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

    // Module bundle route groups will be registered here, one per delivery:
    // Bundle 2 (HR & Recruitment), Bundle 3 (Elections), ...
});
