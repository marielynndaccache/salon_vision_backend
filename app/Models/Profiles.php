<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Profiles extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'name',
        'upload_order_document',
        'upload_pod',
        'upload_order_by_excel',
        'download_orders_file',
        'download_pod',
        'download_order_document',
        'print_awb',
        'customer_note',
        'special_request',
        'cancel_order',
        'delete_order',
        'retry_dms',
        'retry_wms',
        'update_dms_actions',
        'on_hold_customer_take_actions',
        'revoke_orders'
    ];
}
