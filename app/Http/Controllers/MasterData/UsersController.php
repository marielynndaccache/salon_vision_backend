<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Repositories\MasterData\Users\UsersRepository;
use App\Models\User;
use Validator;

class UsersController extends Controller
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
        $UsersRepository = new UsersRepository();
        $users = $UsersRepository->getUsers();
        return response()->json($users);
    }

            /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataBySearchQuery(Request $request)
    {
        $UsersRepository = new UsersRepository();
        $data = $UsersRepository->getDataBySearchQuery($request);
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById($id)
    {
        $UsersRepository = new UsersRepository();
        $user = $UsersRepository->getById($id);
        return response()->json($user);
    }
    
   /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'profile_id' => 'required',
            'role_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'profile_id' => $request->get('profile_id'),
            'department_id' => $request->get('department_id'),
            'role_id' => (int) $request->get('role_id'),
        ];
        User::create($data);
        return response()->json(['success' => 1]);
    }

        /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->get("id"),
            'profile_id' => 'required',
            'role_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $User = User::Find($request->get('id'));
        $User->name = $request->get('name');
        $User->email = $request->get('email');
        $User->profile_id = $request->get('profile_id');
        $User->department_id = $request->get('department_id');
        $User->role_id = (int) $request->get('role_id');
        $User->save();
        return response()->json(['success' => 1]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request){
        $id = $request->get('id');
        User::where('id', $id)->delete();
        return response()->json(['success' => 1]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $id = $request->get('id');
        $user = User::Find($request->get('id'));
        $user->password = bcrypt($request->get('password'));
        $user->save();
        return response()->json(['success' => 1]);
    }
}
