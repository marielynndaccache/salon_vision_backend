<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsProjects extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'name',
        'name_ar',
        'customer_id',
        'company_id',
    ];
}
