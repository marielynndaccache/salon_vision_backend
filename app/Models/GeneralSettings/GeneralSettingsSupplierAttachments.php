<?php

namespace App\Models\GeneralSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneralSettingsSupplierAttachments extends Model
{
    use HasFactory,Notifiable;
    protected $table = "general_settings_supplier_attachments";

    protected $fillable = [
        'file_name',
        'file_extension',
        'file_preview_name',
        'supplier_id',
        'created_by',
    ];
}
