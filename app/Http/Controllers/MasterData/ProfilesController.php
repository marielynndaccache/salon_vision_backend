<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Repositories\MasterData\Profiles\ProfilesRepository;
use App\Models\Profiles;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ProfilePermissions;
use App\Models\Permissions;

class ProfilesController extends Controller
{
    /**
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
        $ProfilesRepository = new ProfilesRepository();
        $users = $ProfilesRepository->getProfiles();
        return response()->json($users);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById($id)
    {
        $ProfilesRepository = new ProfilesRepository();
        $user = $ProfilesRepository->getById($id);
        return response()->json($user);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = [
            'name' => $request->get('name'),
        ];
        Profiles::create($data);
        return response()->json(['success' => 1]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $profile = Profiles::Find($request->get('id'));
        $profile->name = $request->get('name');
        $profile->save();

        return response()->json(['success' => 1]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $id = $request->get('id');
        Profiles::where('id', $id)->delete();
        return response()->json(['success' => 1]);
    }


    public function buildTree(array &$flat, $parentId = 0, $full_path = "", $parents_ids = [])
    {
        $branch = array();

        foreach ($flat as $element) {
            $element["full_path"] = "";
            $element["parents_ids"] = $parents_ids;
            if ($element['parent_permission_id']) {
                $element["parents_ids"][] = $element['parent_permission_id'];
            }
            if ($element['parent_permission_id'] == $parentId) {
                $children = $this->buildTree($flat, $element['id'], $full_path . "/" . $element["path"], $element["parents_ids"]);
                if ($children) {
                    $element['children'] = $children;
                }
                $element["full_path"] = $full_path . "/" . $element["path"];
                $branch[$element['id']] = $element;
            // unset($flat[$element['id']]);
            }
        }
        return $branch;
    }

    public function getPermissionsTree($profile_id)
    {
        $permissions = Permissions::Where("is_active", 1)->get()->toArray();
        $permissions = json_decode(json_encode($permissions), true);
        $tree = $this->buildTree($permissions);
        $tree = json_decode(json_encode(array_values($tree)), true);
        $ProfilePermissions = ProfilePermissions::Where("profile_id", $profile_id)->get()->toArray();
        $ProfilePermissionsIds = array_column($ProfilePermissions, "permission_id");
        return response()->json(["tree" => $tree, "selected_ids" => $ProfilePermissionsIds]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required',
            'permission_ids' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $permission_ids = $request->get('permission_ids');
        $profile_id = $request->get('profile_id');
        $insert_arr = [];
        foreach ($permission_ids as $permission_id) {
            $insert_arr[] = [
                "profile_id" => $profile_id,
                "permission_id" => $permission_id,
            ];
        }
        ProfilePermissions::upsert($insert_arr, ["profile_id", "permission_id"]);
        ProfilePermissions::Where("profile_id", $profile_id)->WhereNotIn("permission_id", $permission_ids)->delete();
        return response()->json(['success' => 1]);
    }

}
