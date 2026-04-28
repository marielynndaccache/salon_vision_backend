<?php

namespace App\Http\Controllers\AI\Settings;

use App\Models\AI\Service;
use Illuminate\Http\Request;
use Validator;

class AIServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json(Service::query()->orderBy('id')->get());
    }

    public function getById($id)
    {
        return response()->json(Service::find($id));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        Service::create([
            'name' => $request->get('name'),
            'price' => (int) $request->get('price'),
            'description' => $request->get('description'),
        ]);

        return response()->json(['success' => 1]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        Service::where('id', $request->get('id'))->update([
            'name' => $request->get('name'),
            'price' => (int) $request->get('price'),
            'description' => $request->get('description'),
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
        Service::where('id', $request->get('id'))->delete();

        return response()->json(['success' => 1]);
    }
}
