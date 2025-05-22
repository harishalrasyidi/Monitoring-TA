<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasUserModel extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_kota_has_user';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_kota',
        'id_user',
    ];
}
