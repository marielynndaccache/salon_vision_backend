<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsCompaniesAccountsConfigs extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'company_id',
        'transaction_type',
        'currency_id',
        'accounting_chart_of_accounts_id',
    ];
}
