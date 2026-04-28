<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsLocations extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'name_ar',
        'company_id',
        'lat',
        'lon',
        'type',
        'code',
        'country_id',
        'governorate_id',
        'neighborhood_id',
        'address',
        'phone',
        'ref_id',
        'location_group_id',
        'location_category_id',
        'parent_location_id',
        'national_address_code',
    ];
}
