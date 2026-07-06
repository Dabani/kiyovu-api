<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseModuleController extends Controller
{
    /** @return class-string<\Illuminate\Database\Eloquent\Model> */
    abstract protected function modelClass(): string;

    /** @return class-string<JsonResource> */
    abstract protected function resourceClass(): string;

    /** Columns eligible for the free-text `search` param (whitelisted, never raw user input in SQL). */
    abstract protected function searchableColumns(): array;

    /** Relations always eager-loaded for list + show, to avoid N+1 across every screen. */
    protected function withRelations(): array
    {
        return [];
    }

    /** Extra query filters, e.g. ?status_id=3, ?category_id=2 — whitelisted keys only. */
    protected function filterableColumns(): array
    {
        return [];
    }

    /** Override to inject extra constraints (e.g. scope self-service roles to their own records). */
    protected function baseQuery(Request $request): Builder
    {
        $modelClass = $this->modelClass();

        return $modelClass::query();
    }

    public function index(Request $request)
    {
        $query = $this->baseQuery($request)->with($this->withRelations());

        if ($request->filled('search')) {
            $term = '%'.$request->string('search').'%';
            $query->where(function (Builder $q) use ($term) {
                foreach ($this->searchableColumns() as $column) {
                    $q->orWhere($column, 'like', $term);
                }
            });
        }

        foreach ($this->filterableColumns() as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->input($column));
            }
        }

        $query = $this->applyReportPeriod($query, $request);

        $perPage = (int) $request->input('per_page', 20);
        $paginated = $query->latest('id')->paginate($perPage);

        $resourceClass = $this->resourceClass();

        return $resourceClass::collection($paginated);
    }

    /** Permission checked before delete when the model has no dedicated Policy registered. */
    protected function deletePermission(): ?string
    {
        return null;
    }

    public function destroy(int $id)
    {
        $modelClass = $this->modelClass();
        $record = $modelClass::findOrFail($id);

        if ($permission = $this->deletePermission()) {
            \Illuminate\Support\Facades\Gate::authorize($permission);
        } else {
            $this->authorize('delete', $record);
        }

        $record->delete();

        return response()->noContent();
    }

    /**
     * Shared period-filter logic for the report picker: daily/weekly/monthly/
     * quarterly/annual/custom, all applied against the model's `created_at`
     * unless the controller overrides reportDateColumn().
     */
    protected function applyReportPeriod(Builder $query, Request $request): Builder
    {
        if (! $request->filled('period')) {
            return $query;
        }

        $column = $this->reportDateColumn();
        $now = now();

        return match ($request->string('period')->toString()) {
            'daily' => $query->whereDate($column, $now->toDateString()),
            'weekly' => $query->whereBetween($column, [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]),
            'monthly' => $query->whereYear($column, $now->year)->whereMonth($column, $now->month),
            'quarterly' => $query->whereBetween($column, [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()]),
            'annual' => $query->whereYear($column, $now->year),
            'custom' => $query->whereBetween($column, [
                $request->date('from') ?? $now->copy()->subMonth(),
                $request->date('to') ?? $now,
            ]),
            default => $query,
        };
    }

    protected function reportDateColumn(): string
    {
        return 'created_at';
    }

    /**
     * Renders a PDF for controllers that don't need a bespoke report view —
     * pass a title, the already period-filtered query, and a [Label => dot.path] column map.
     */
    protected function genericReport(Request $request, Builder $query, string $title, array $columns, string $filenamePrefix)
    {
        $query = $this->applyReportPeriod($query, $request);
        $rows = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.simple-list', [
            'rows' => $rows,
            'columns' => $columns,
            'reportTitle' => $title,
            'period' => $request->string('period', 'all')->toString(),
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download("{$filenamePrefix}-report-".now()->format('Y-m-d').'.pdf');
    }
}
