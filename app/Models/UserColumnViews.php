<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserColumnViews extends Model
{
    use HasFactory;

    protected $table = "user_column_views";

    protected $fillable = [
        'user_id',
        'table_key',
        'columns',
    ];

    protected $casts = [
        'columns' => 'array',
    ];
}
