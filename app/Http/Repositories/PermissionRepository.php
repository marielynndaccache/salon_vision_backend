<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PermissionRepository
{
    public function __construct()
    {
    }

    public function getCompanies()
    {
        $user = auth()->user();
        // $user_id = $user->id;
        $user_profile_id = $user->profile_id;
        // cache for 6 hours
        return Cache::remember("user_companies_$user_profile_id", 21600, function () use ($user_profile_id) {
            $sql = "SELECT gsc.*
                    FROM general_settings_companies gsc
                    JOIN profile_companies pc ON (
                        (
                            (gsc.level_type = 'COMPANY' AND pc.company_id = gsc.id)
                        )
                        AND pc.profile_id = '$user_profile_id')
                    ORDER BY gsc.name";
            return DB::select($sql);
        });
    }

    public function getLocations()
    {
        $user = auth()->user();
        // $user_id = $user->id;
        $user_profile_id = $user->profile_id;
        // cache for 6 hours
        return Cache::remember("user_locations_$user_profile_id", 21600, function () use ($user_profile_id) {
            $sql = "SELECT gsl.*
                    FROM general_settings_locations gsl
                    JOIN profile_locations pl ON (pl.location_id = gsl.id AND pl.profile_id = '$user_profile_id')
                    ORDER BY gsl.name";
            return DB::select($sql);
        });
    }
}
