<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProfileCompanies extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'profile_id',
        'company_id',
    ];
}
