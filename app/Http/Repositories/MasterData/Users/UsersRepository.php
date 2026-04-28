<?php

namespace App\Http\Repositories\MasterData\Users;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Services\Helpers;

class UsersRepository
{
    public function __construct()
    {
    }
   
    public function getUsers()
    {   
        $sql = "SELECT u.*, p.name as profile_name, ur.name as role_name
                FROM users u
                LEFT JOIN profiles p ON (u.profile_id = p.id)
                LEFT JOIN users_roles ur ON (u.role_id = ur.id)
                WHERE 1=1
                ORDER BY u.id";
        return DB::select($sql);
    }

    public function getById($id)
    {   
        $sql = "SELECT u.*
                FROM users u
                WHERE u.id = :id";
        return DB::select($sql,[":id"=>$id])[0];
    }

    public function getDataBySearchQuery(Request $request)
    {
        $Helpers = new Helpers();
        $filter = "";
        $filterParams = [];
        if ($request->get('searchQuery')) {
            $searchQuery = $request->get('searchQuery');
            $searchQuery = json_decode($searchQuery, true);
            $q = $searchQuery["q"];
            $selected_values = $searchQuery["selectedValues"];
            if ($q || sizeof($selected_values)) {
                $filter .= " AND (";
                if ($q) {
                    $filter .= " (u.name LIKE :q) ";
                    $filterParams["q"] = "%$q%";
                }
                if (sizeof($selected_values) > 0) {
                    $selected_values = $Helpers->addQuota($selected_values);
                    if ($q) {
                        $filter .= " OR u.id IN ($selected_values)";
                    }
                    else {
                        $filter .= " u.id IN ($selected_values)";
                    }
                }
                $filter .= ")";
            }
        }
        if ($request->get('department_id')) {
            $filter .= " AND u.department_id = :department_id";
            $filterParams["department_id"] = $request->get("department_id");
        }

        $sql = "SELECT u.*
                FROM users u
                WHERE 1=1 
                $filter
                ORDER BY u.name
                LIMIT 0, 20
                ";
        return DB::select($sql, $filterParams);
    }
}
