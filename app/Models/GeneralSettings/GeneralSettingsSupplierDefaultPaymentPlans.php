<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsSupplierDefaultPaymentPlans extends Model
{
    use HasFactory, Notifiable;

    protected $table = "general_settings_supplier_default_payment_plans";

    protected $fillable = [
        'supplier_id',
        'to_be_paid_percentage',
        'due_after_days',
    ];
}
