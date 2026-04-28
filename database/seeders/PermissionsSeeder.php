<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    private function getPermissionsFromTree($tree, &$arr, $parent_full_path = "")
    {
        foreach ($tree as $treeValue) {
            if (isset($treeValue["details"])) {
                $temp = [
                    "name" => $treeValue["name"],
                    "name_ar" => $treeValue["name_ar"],
                    "permission_type" => $treeValue["permission_type"],
                    "path" => $treeValue["path"],
                    "icon" => $treeValue["icon"],
                    "file_path" => $treeValue["file_path"],
                    "full_path" => $parent_full_path . "/" . $treeValue["path"],
                ];
                $arr[] = $temp;
                if (in_array($treeValue["permission_type"], ["page", "sub_page"])) {
                    $arr[] = [
                        "name" => "Add",
                        "name_ar" => "",
                        "permission_type" => "Add",
                        "path" => "Add",
                        "icon" => null,
                        "file_path" => null,
                        "full_path" => $parent_full_path . "/" . $treeValue["path"] . "/Add",
                    ];
                    $arr[] = [
                        "name" => "Update",
                        "name_ar" => "",
                        "permission_type" => "Update",
                        "path" => "Update",
                        "icon" => null,
                        "file_path" => null,
                        "full_path" => $parent_full_path . "/" . $treeValue["path"] . "/Update",
                    ];
                    $arr[] = [
                        "name" => "Delete",
                        "name_ar" => "",
                        "permission_type" => "Delete",
                        "path" => "Delete",
                        "icon" => null,
                        "file_path" => null,
                        "full_path" => $parent_full_path . "/" . $treeValue["path"] . "/Delete",
                    ];
                }
                $this->getPermissionsFromTree($treeValue["details"], $arr, $temp["full_path"]);
            } else {
                $temp = [
                    "name" => $treeValue["name"],
                    "name_ar" => $treeValue["name_ar"],
                    "permission_type" => $treeValue["permission_type"],
                    "path" => $treeValue["path"],
                    "icon" => $treeValue["icon"],
                    "file_path" => $treeValue["file_path"],
                    "full_path" => $parent_full_path . "/" . $treeValue["path"],
                ];
                $arr[] = $temp;
                if (in_array($treeValue["permission_type"], ["page", "sub_page"])) {
                    $arr[] = [
                        "name" => "Add",
                        "name_ar" => "",
                        "permission_type" => "Add",
                        "path" => "Add",
                        "icon" => null,
                        "file_path" => null,
                        "full_path" => $parent_full_path . "/" . $treeValue["path"] . "/Add",
                    ];
                    $arr[] = [
                        "name" => "Update",
                        "name_ar" => "",
                        "permission_type" => "Update",
                        "path" => "Update",
                        "icon" => null,
                        "file_path" => null,
                        "full_path" => $parent_full_path . "/" . $treeValue["path"] . "/Update",
                    ];
                    $arr[] = [
                        "name" => "Delete",
                        "name_ar" => "",
                        "permission_type" => "Delete",
                        "path" => "Delete",
                        "icon" => null,
                        "file_path" => null,
                        "full_path" => $parent_full_path . "/" . $treeValue["path"] . "/Delete",
                    ];
                }
            }
        }
    }
    public function run(): void
    {
        $permissons_arr = [
            [
                "name" => "Home",
                "name_ar" => null,
                "permission_type" => "report",
                "path" => "",
                "icon" => "cilSpeedometer",
                "file_path" => "views/Home/Home",
            ],
            require_once(__DIR__ . "/ModulesPermissions/MasterDataPermissions.php"),
            require_once(__DIR__ . "/ModulesPermissions/AIPermissions.php"),

        ];
        $insert_arr = [];
        $this->getPermissionsFromTree($permissons_arr, $insert_arr);
        DB::table('permissions')->upsert($insert_arr, ["full_path"]);
        DB::table('permissions')->whereNotIn("full_path", array_column($insert_arr, "full_path"))->delete();
        $sql = "UPDATE permissions p1
                INNER JOIN permissions p2 ON (TRIM(TRAILING '/' FROM TRIM(TRAILING p2.path FROM p2.full_path)) = p1.full_path)
                SET p2.parent_permission_id = p1.id";

        DB::statement($sql);
        DB::statement("INSERT INTO profile_permissions (permission_id,profile_id) 
                        SELECT id,1
                        FROM permissions
                        WHERE id NOT IN (SELECT permission_id FROM profile_permissions WHERE profile_id = 1)");
        DB::statement("DELETE FROM profile_permissions WHERE permission_id NOT IN (SELECT id FROM permissions)");
    }
}
