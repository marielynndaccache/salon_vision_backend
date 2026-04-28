<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsExchangeRate extends Model
{
    use HasFactory,Notifiable;
    protected $table ="general_settings_exchange_rate";

    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'rate',
        'date',
    ];
}
