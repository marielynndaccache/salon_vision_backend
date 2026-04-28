<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PersonalizedPages extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'page_label',
        'page_description',
        'page_url',
        'page_position',
        'filters',
        'user_id',
        'profile_ids',
        'created_by',
    ];
}
