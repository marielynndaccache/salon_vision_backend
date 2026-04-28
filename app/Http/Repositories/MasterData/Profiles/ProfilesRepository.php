<?php

namespace App\Http\Repositories\MasterData\Profiles;

use Illuminate\Support\Facades\DB;

class ProfilesRepository
{
    public function __construct()
    {
    }

    public function getProfiles()
    {   
        $sql = "SELECT p.*
                FROM profiles p
                WHERE 1=1
                ORDER BY p.id";
        return DB::select($sql);
    }

    public function getById($id)
    {   
        $sql = "SELECT p.*
                FROM profiles p
                WHERE p.id = :id
                ORDER BY p.id DESC";
        return DB::select($sql,[":id"=>$id])[0];
    }
}
