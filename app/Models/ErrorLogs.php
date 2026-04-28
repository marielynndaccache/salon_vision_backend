<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ErrorLogs extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'parent',
        'error_type',
        'error_value',
        'payload',
        'created_at',
    ];
}
