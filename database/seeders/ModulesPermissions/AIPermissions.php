<?php

return [
    "name" => "Detections",
    "name_ar" => null,
    "permission_type" => "group",
    "path" => "AI",
    "icon" => "cilLightbulb",
    "file_path" => null,
    "details" => [
        [
            "name" => "Settings",
            "name_ar" => null,
            "permission_type" => "group",
            "path" => "Settings",
            "icon" => null,
            "file_path" => null,
            "details" => [
                [
                    "name" => "Services",
                    "name_ar" => null,
                    "permission_type" => "page",
                    "path" => "Services",
                    "icon" => null,
                    "file_path" => "views/AI/Settings/Services/Services",
                ],
            ],
        ],
        [
            "name" => "Operations",
            "name_ar" => null,
            "permission_type" => "group",
            "path" => "Operations",
            "icon" => null,
            "file_path" => null,
            "details" => [
                [
                    "name" => "Employee Inputs",
                    "name_ar" => null,
                    "permission_type" => "page",
                    "path" => "EmployeeInputs",
                    "icon" => null,
                    "file_path" => "views/AI/Operations/EmployeeInputs/EmployeeInputs",
                ],
            ],
        ],
        [
            "name" => "Reports",
            "name_ar" => null,
            "permission_type" => "group",
            "path" => "Reports",
            "icon" => null,
            "file_path" => null,
            "details" => [
                [
                    "name" => "Model Results Vs Inputs",
                    "name_ar" => null,
                    "permission_type" => "page",
                    "path" => "ModelResultsVsInputs",
                    "icon" => null,
                    "file_path" => "views/AI/Reports/ModelResultsVsInputs/ModelResultsVsInputs",
                ],
            ],
        ],
    ],
];
