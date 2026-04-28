<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Permissions extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'name',
        'name_ar',
        'permission_type',
        'path',
        'parent_permission_id',
        'icon',
        'file_path',
        'full_path',
        'is_active',
    ];
}
