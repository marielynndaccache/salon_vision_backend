<?php

namespace App\Http\Controllers\AI\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIModelResultsReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        $from = $request->get('from') ?: $request->get('from_date');
        $to = $request->get('to') ?: $request->get('to_date');

        $employeeInputsQuery = DB::table('employee_inputs')
            ->select('service_id', DB::raw('COUNT(*) as employee_inputs_count'));

        $modelResultsQuery = DB::table('model_results')
            ->select('service_id', DB::raw('COUNT(*) as model_results_count'));

        if ($from) {
            $employeeInputsQuery->where('datetime', '>=', $from);
            $modelResultsQuery->where('datetime', '>=', $from);
        }
        if ($to) {
            $employeeInputsQuery->where('datetime', '<=', $to);
            $modelResultsQuery->where('datetime', '<=', $to);
        }

        $user = $request->user();
        if (!$user->isAdmin()) {
            $employeeInputsQuery->where('user_id', $user->id);
        }

        $employeeInputsQuery->groupBy('service_id');
        $modelResultsQuery->groupBy('service_id');

        $rows = DB::table('services as s')
            ->leftJoinSub($employeeInputsQuery, 'ei', function ($join) {
                $join->on('ei.service_id', '=', 's.id');
            })
            ->leftJoinSub($modelResultsQuery, 'mr', function ($join) {
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

        return response()->json($rows);
    }
}
