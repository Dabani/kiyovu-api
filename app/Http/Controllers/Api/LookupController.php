<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lookups\LuDocumentClassification;
use App\Models\Lookups\LuLanguage;
use App\Models\Lookups\LuStatus;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LookupController extends Controller
{
    /**
     * Maps a URL-friendly key to its dedicated Eloquent model.
     * Every module bundle delivery adds its own entries here
     * (e.g. 'membership-categories' => LuMembershipCategory::class).
     * The frontend never hardcodes options — it always calls
     * GET /api/lookups/{key} and renders whatever comes back.
     */
    private const MAP = [
        'languages' => LuLanguage::class,
        'statuses' => LuStatus::class,
        'document-classifications' => LuDocumentClassification::class,
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
