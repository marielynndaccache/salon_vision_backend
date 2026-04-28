<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class IntegrationsApisLogs extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'ref_key',
        'system_name',
        'url',
        'url_code',
        'request_header',
        'request_payload',
        'response_body',
        'response_http_code',
        'request_duration',
        'company_id',

    ];
}
