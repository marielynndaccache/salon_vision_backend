<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\Accounting\AccountingExpenseCategories;

class GeneralSettingsSuppliers extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'name',
        'name_ar',
        'code',
        'email',
        'contact_person',
        'country_id',
        'governorate_id',
        'address',
        'phone',
        'vat_number',
        'trading_registration_number',
        'description',
        'po_box',
        'zipcode',
        'payments_terms',
        'summary',
        'logo',
        'accounting_raw_materials_goods_chart_of_accounts_id',
        'accounting_vat_raw_materials_goods_chart_of_accounts_id',
        'accounting_fixed_assets_chart_of_accounts_id',
        'accounting_vat_fixed_assets_chart_of_accounts_id',
        'accounting_services_expenses_chart_of_accounts_id',
        'accounting_vat_services_expenses_chart_of_accounts_id',
        'sells_raw_materials_goods',
        'sells_fixed_assets',
        'sells_services',
        'is_subject_to_vat',
        'default_accounting_expense_category_id',
        'company_id',

    ];

    public function DefaultAccountingExpenseCategories()
    {
        return $this->belongsTo(AccountingExpenseCategories::class, 'default_accounting_expense_category_id');
    }
}
