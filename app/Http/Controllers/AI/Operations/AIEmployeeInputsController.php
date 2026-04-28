<?php

namespace App\Http\Controllers\AI\Operations;

use App\Models\AI\EmployeeInput;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class AIEmployeeInputsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        $user = $request->user();

        $query = DB::table('employee_inputs as ei')
            ->leftJoin('users as u', 'u.id', '=', 'ei.user_id')
            ->leftJoin('services as s', 's.id', '=', 'ei.service_id');

        if (!$user->isAdmin()) {
            $query->where('ei.user_id', $user->id);
        }

        $rows = $query->select(
                'ei.id',
                'ei.datetime',
                'ei.created_at',
                'ei.updated_at',
                'u.name as user_name',
                's.name as service_name'
            )
            ->orderByDesc('ei.id')
            ->get();

        return response()->json($rows);
    }

    public function getById(Request $request, $id)
    {
        $q = EmployeeInput::where('id', $id);
        if (!$request->user()->isAdmin()) {
            $q->where('user_id', $request->user()->id);
        }
        $row = $q->first();
        if (!$row) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($row);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'datetime' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $datetime = $request->get('datetime');
        EmployeeInput::create([
            'user_id' => (int) $request->user()->id,
            'service_id' => (int) $request->get('service_id'),
            'datetime' => $datetime ? Carbon::parse($datetime)->format('Y-m-d H:i:s') : null,
        ]);

        return response()->json(['success' => 1]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'service_id' => 'required|integer',
            'datetime' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $q = EmployeeInput::where('id', $request->get('id'));
        if (!$request->user()->isAdmin()) {
            $q->where('user_id', $request->user()->id);
        }
        $row = $q->first();
        if (!$row) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $datetime = $request->get('datetime');
        $row->update([
            'service_id' => (int) $request->get('service_id'),
            'datetime' => $datetime ? Carbon::parse($datetime)->format('Y-m-d H:i:s') : null,
        ]);

        return response()->json(['success' => 1]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $q = EmployeeInput::where('id', $request->get('id'));
        if (!$request->user()->isAdmin()) {
            $q->where('user_id', $request->user()->id);
        }
        $deleted = $q->delete();
        if (!$deleted) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['success' => 1]);
    }
}
