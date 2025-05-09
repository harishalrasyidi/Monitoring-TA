<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatalogTA extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_katalog_ta';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_kota',
        'judul_ta',
        'deskripsi',
        'file_ta',
        'waktu_pengumpulan',
    ];
    
    // Relasi ke tabel kota
    public function kota()
    {
        return $this->belongsTo(KotaModel::class, 'id_kota', 'id_kota');
    }
    
    // Relasi ke anggota TA
    public function anggota()
    {
        return $this->hasMany(KotaHasUserModel::class, 'id_kota', 'id_kota')->with('user');
    }

    public function user()
    {
    return $this->belongsTo(User::class, 'id_user');
    }

    public function penulis()
{
    return $this->belongsToMany(User::class, 'tbl_kota_has_user', 'id_katalog', 'id_user');
}

}