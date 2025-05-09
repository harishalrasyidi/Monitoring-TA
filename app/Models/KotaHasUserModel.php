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
        'id_katalog',
    ];
    
    // No timestamps if your table doesn't have them
    public $timestamps = false;
    
    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
    
    // Relation to Kota
    public function kota()
    {
        return $this->belongsTo(KotaModel::class, 'id_kota', 'id_kota');
    }
    
    // Relation to Katalog TA
    public function katalog()
    {
        return $this->belongsTo(KatalogTA::class, 'id_katalog', 'id');
    }
}