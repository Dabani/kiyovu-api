<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lookups\LuDisputeGround;
use App\Models\Lookups\LuDocumentClassification;
use App\Models\Lookups\LuElectedPosition;
use App\Models\Lookups\LuEmploymentType;
use App\Models\Lookups\LuFeeTier;
use App\Models\Lookups\LuGiftDisposition;
use App\Models\Lookups\LuHqPosition;
use App\Models\Lookups\LuLanguage;
use App\Models\Lookups\LuMembershipCategory;
use App\Models\Lookups\LuNomineeType;
use App\Models\Lookups\LuPaymentMethod;
use App\Models\Lookups\LuStatus;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LookupController extends Controller
{
    private const MAP = [
        'languages' => LuLanguage::class,
        'statuses' => LuStatus::class,
        'document-classifications' => LuDocumentClassification::class,
        // Bundle 1 — Membership & Honorary
        'membership-categories' => LuMembershipCategory::class,
        'fee-tiers' => LuFeeTier::class,
        'payment-methods' => LuPaymentMethod::class,
        'nominee-types' => LuNomineeType::class,
        // Bundle 2 — HR & Recruitment
        'hq-positions' => LuHqPosition::class,
        'employment-types' => LuEmploymentType::class,
        'gift-dispositions' => LuGiftDisposition::class,
        // Bundle 3 — Elections
        'elected-positions' => LuElectedPosition::class,
        'dispute-grounds' => LuDisputeGround::class,
    ];

    public function index(string $key, Request $request)
    {
        $modelClass = self::MAP[$key] ?? null;

        if (! $modelClass) {
            throw new NotFoundHttpException("Unknown lookup key: {$key}");
        }

        $query = $modelClass::query()->active()->ordered();

        // e.g. GET /api/lookups/statuses?applies_to=members
        if ($request->filled('applies_to') && method_exists($modelClass, 'scopeAppliesTo')) {
            $query->appliesTo($request->string('applies_to'));
        }

        return response()->json(
            $query->get(['id', 'code', 'label_en', 'label_fr', 'label_rw'])
        );
    }
}
