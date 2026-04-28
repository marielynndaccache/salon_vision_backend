<?php

return [
    "name" => "Master Data",
    "name_ar" => null,
    "permission_type" => "group",
    "path" => "MasterData",
    "icon" => "cilPuzzle",
    "file_path" => null,
    "details" => [
        [
            "name" => "Users Management",
            "name_ar" => null,
            "permission_type" => "group",
            "path" => "UsersManagement",
            "icon" => null,
            "file_path" => null,
            "details" => [
                [
                    "name" => "Users",
                    "name_ar" => null,
                    "permission_type" => "page",
                    "path" => "Users",
                    "icon" => null,
                    "file_path" => "views/MasterData/UsersManagement/Users/Users",
                    "details" => [
                        [
                            "name" => "Change Password",
                            "name_ar" => null,
                            "permission_type" => "actionButton",
                            "path" => "ChangePassword",
                            "icon" => null,
                            "file_path" => null,
                        ]
                    ],
                ],
                [
                    "name" => "Profiles",
                    "name_ar" => null,
                    "permission_type" => "page",
                    "path" => "Profiles",
                    "icon" => null,
                    "file_path" => "views/MasterData/UsersManagement/Profiles/Profiles",
                    "details" => [
                        [
                            "name" => "Adjust Permissions",
                            "name_ar" => null,
                            "permission_type" => "actionButton",
                            "path" => "AdjustPermissions",
                            "icon" => null,
                            "file_path" => null,
                        ]
                    ],
                ],
            ],
        ],
    ],
];