<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsSchemas extends Model
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
        'schema_group_id',
        'parent_schema_id',
        'has_shipping_address',
    ];

    public function location()
    {
        return $this->belongsTo('App\Models\GeneralSettings\GeneralSettingsLocations', "id","ref_id")->WhereIn("type", ["schema", "warehouse", "branch"]);
    }
}
