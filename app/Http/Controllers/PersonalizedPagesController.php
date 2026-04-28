<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\PersonalizedPages;


class PersonalizedPagesController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $auth_user = auth()->user();
        return response()->json(PersonalizedPages::Where("user_id", $auth_user->id)->OrWhereRaw('FIND_IN_SET(?, profile_ids)', [$auth_user->profile_id])->get());
    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById($id)
    {
        return response()->json(PersonalizedPages::find($id));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_label' => 'required',
            'page_description' => 'required',
            'page_url' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $auth_user = auth()->user();
        $profile_ids = null;
        if ($request->get("profile_ids")) {
            $profile_ids = implode(",", $request->get("profile_ids"));
        }
        $data = [
            'page_label' => $request->get('page_label'),
            'page_description' => $request->get('page_description'),
            'page_url' => $request->get('page_url'),
            'page_position' => $request->get('page_position'),
            'filters' => json_encode($request->get('filters')),
            'user_id' => $profile_ids ? null : $auth_user->id,
            'profile_ids' => $profile_ids,
            'created_by' => $auth_user->id,
        ];
        PersonalizedPages::create($data);
        return response()->json(['success' => 1]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'page_label' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $profile_ids = null;
        if ($request->get("profile_ids")) {
            $profile_ids = implode(",", $request->get("profile_ids"));
        }
        $model = PersonalizedPages::Find($request->get('id'));
        $model->page_label = $request->get('page_label');
        $model->page_description = $request->get('page_description');
        $model->filters = $request->get('filters');
        $model->profile_ids = $profile_ids;
        if ($profile_ids) {
            $model->user_id = null;
        }
        $model->save();
        return response()->json(['success' => 1]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $id = $request->get('id');
        PersonalizedPages::where('id', $id)->delete();
        return response()->json(['success' => 1]);
    }
}