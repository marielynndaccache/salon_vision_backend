<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsDepartments extends Model
{
    use HasFactory,Notifiable;
    protected $table = "general_settings_departments";

    protected $fillable = [
        'name',
        'company_id',
        'head_of_department_user_ids',
    ];
}
