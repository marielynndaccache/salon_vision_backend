<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Laravel\Passport\Passport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MasterData\ProfilesController;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\TokenRepository;
use Psr\Http\Message\ServerRequestInterface;
use App\Models\NotificationsCenter\NotificationsCenterNotifications;

class UserAuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);
        Passport::personalAccessTokensExpireIn(Carbon::now()->addHours(14));
        $token = $user->createToken('API Token')->accessToken;

        return response(['user' => $user, 'token' => $token]);
    }

    public function login(Request $request, AuthorizationServer $server, TokenRepository $tokens, ServerRequestInterface $request2)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::Where("email", $request->get("email"))->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized - User not found'], 401);
        }
        $PassportAuthController = new PassportAuthController($server, $tokens);
        $token_response = $PassportAuthController->login($request2);
        if ($token_response->status() != 200) {
            return response()->json(['error' => 'Unauthorized', 'erro_info' => json_decode($token_response->getContent(), true)], 401);
        }
        $token = json_decode($token_response->getContent(), true);
        $PermissionsArr = $this->getPermissions($user);
        return response([
            'user' => $user,
            'access_token' => $token["access_token"],
            'profile_data' => $PermissionsArr['profile_data'],
            'personalized_pages' => $PermissionsArr['personalized_pages'],
        ]);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return auth()->createNewToken(auth()->refresh());
    }

    public function setFirebaseToken(Request $request)
    {
        $user = auth()->user();
        $user->firebase_token = $request->get("token");
        $user->save();
        return response()->json(['User Token Updated Successfully']);
    }

    public function getPermissionsFromTree($tree, &$fullPaths, &$filePaths)
    {
        foreach ($tree as $treeValue) {
            if (isset($treeValue["children"])) {
                if (!in_array($treeValue["permission_type"], ["page", "report", "group", "sub_page"])) {
                    $fullPaths[] = $treeValue["full_path"];
                }
                $filePaths[] = ["element_path" => $treeValue["file_path"], "path" => $treeValue["full_path"], "name" => $treeValue["name"]];
                $this->getPermissionsFromTree($treeValue["children"], $fullPaths, $filePaths);
            } else {
                if (!in_array($treeValue["permission_type"], ["page", "report", "group", "sub_page"])) {
                    $fullPaths[] = $treeValue["full_path"];
                }
                $filePaths[] = ["element_path" => $treeValue["file_path"], "path" => $treeValue["full_path"], "name" => $treeValue["name"]];
            }
        }
    }

    public function userProfile()
    {
        $user = auth()->user();
        $user_id = $user->id;
        $profile_id = $user->profile_id;
        $sql = "SELECT 
                p.id,
                p.name,
                p.name_ar,
                p.permission_type,
                p.path,
                p.icon,
                p.parent_permission_id,
                p.file_path
                FROM profile_permissions pp
                JOIN permissions p ON (p.id = pp.permission_id AND p.is_active = 1)
                WHERE pp.profile_id = :profile_id
                ORDER BY p.id";
        $permissions = DB::select($sql, ["profile_id" => $profile_id]);
        $permissions = json_decode(json_encode($permissions), true);
        $routes = [];
        $ProfilesController = new ProfilesController();
        $tree = $ProfilesController->buildTree($permissions);
        $flat_permissions = [];
        $this->getPermissionsFromTree($tree, $flat_permissions, $routes);
        return response()->json([
            "userData" => $user,
            "permissions" => $flat_permissions,
            "nav" => array_values($tree),
            "routes" => $routes,
        ]);
    }

    /**
     * @return array
     */
    protected function getPermissions($user)
    {
        // $user = auth()->user();
        $user_id = $user->id;
        $profile_id = $user->profile_id;

        $sql = "SELECT * FROM profiles WHERE id = :profile_id";
        $profile_data = DB::select($sql, ["profile_id" => $profile_id])[0];

        $personalized_pages = DB::select("SELECT * FROM personalized_pages WHERE (user_id = :user_id OR FIND_IN_SET(:profile_id,profile_ids))", ["user_id" => $user_id, "profile_id" => $profile_id]);
        return [
            'profile_data' => $profile_data,
            'personalized_pages' => $personalized_pages,
        ];
    }

}