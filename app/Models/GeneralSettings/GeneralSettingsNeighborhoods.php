<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsNeighborhoods extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'country_id',
        'zipcode',
        'governorate_id',
        'lat',
        'lon',
    ];
}
