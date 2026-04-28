<?php

namespace App\Http\Controllers\AI\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Admin-only aggregates for the home dashboard (date range on employee_inputs / model_results datetime).
     */
    public function summary(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $from = $request->get('from') ?: $request->get('from_date');
        $to = $request->get('to') ?: $request->get('to_date');

        $fromBound = $from ? Carbon::parse($from)->startOfDay()->format('Y-m-d H:i:s') : null;
        $toBound = $to ? Carbon::parse($to)->endOfDay()->format('Y-m-d H:i:s') : null;

        $employeeInputsInRange = function () use ($fromBound, $toBound) {
            $q = DB::table('employee_inputs');
            if ($fromBound) {
                $q->where('datetime', '>=', $fromBound);
            }
            if ($toBound) {
                $q->where('datetime', '<=', $toBound);
            }

            return $q;
        };

        $modelResultsInRange = function () use ($fromBound, $toBound) {
            $q = DB::table('model_results');
            if ($fromBound) {
                $q->where('datetime', '>=', $fromBound);
            }
            if ($toBound) {
                $q->where('datetime', '<=', $toBound);
            }

            return $q;
        };

        $totals = [
            'employee_inputs' => $employeeInputsInRange()->count(),
            'model_results' => $modelResultsInRange()->count(),
            'active_users' => (int) $employeeInputsInRange()->selectRaw('COUNT(DISTINCT user_id) as c')->value('c'),
        ];

        $employeeInputsByService = DB::table('employee_inputs')
            ->select('service_id', DB::raw('COUNT(*) as employee_inputs_count'));
        if ($fromBound) {
            $employeeInputsByService->where('datetime', '>=', $fromBound);
        }
        if ($toBound) {
            $employeeInputsByService->where('datetime', '<=', $toBound);
        }
        $employeeInputsByService->groupBy('service_id');

        $modelResultsByService = DB::table('model_results')
            ->select('service_id', DB::raw('COUNT(*) as model_results_count'));
        if ($fromBound) {
            $modelResultsByService->where('datetime', '>=', $fromBound);
        }
        if ($toBound) {
            $modelResultsByService->where('datetime', '<=', $toBound);
        }
        $modelResultsByService->groupBy('service_id');

        $byService = DB::table('services as s')
            ->leftJoinSub($employeeInputsByService, 'ei', function ($join) {
                $join->on('ei.service_id', '=', 's.id');
            })
            ->leftJoinSub($modelResultsByService, 'mr', function ($join) {
                $join->on('mr.service_id', '=', 's.id');
            })
            ->select(
                's.id as service_id',
                's.name as service_name',
                's.price as service_price',
                DB::raw('COALESCE(ei.employee_inputs_count, 0) as employee_inputs_count'),
                DB::raw('COALESCE(mr.model_results_count, 0) as model_results_count'),
                DB::raw('(COALESCE(mr.model_results_count, 0) - COALESCE(ei.employee_inputs_count, 0)) as count_gap'),
                DB::raw('(COALESCE(ei.employee_inputs_count, 0) * s.price) as employee_inputs_total'),
                DB::raw('(COALESCE(mr.model_results_count, 0) * s.price) as model_results_total'),
                DB::raw('((COALESCE(mr.model_results_count, 0) - COALESCE(ei.employee_inputs_count, 0)) * s.price) as amount_gap')
            )
            ->orderBy('s.name')
            ->get();

        $byUserQuery = DB::table('employee_inputs as ei')
            ->join('users as u', 'u.id', '=', 'ei.user_id')
            ->select('u.id as user_id', 'u.name as user_name', DB::raw('COUNT(ei.id) as inputs_count'))
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('inputs_count')
            ->limit(12);
        if ($fromBound) {
            $byUserQuery->where('ei.datetime', '>=', $fromBound);
        }
        if ($toBound) {
            $byUserQuery->where('ei.datetime', '<=', $toBound);
        }
        $byUser = $byUserQuery->get();

        return response()->json([
            'from_date' => $from ?: null,
            'to_date' => $to ?: null,
            'totals' => $totals,
            'by_service' => $byService,
            'by_user' => $byUser,
        ]);
    }
}
