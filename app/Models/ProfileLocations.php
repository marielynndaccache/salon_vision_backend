<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProfileLocations extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'profile_id',
        'location_id',
    ];
}
