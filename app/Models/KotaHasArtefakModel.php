<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasArtefakModel extends Model
{
    use HasFactory;

    public $timestamps = true; // Karena ada created_at dan updated_at
    protected $table = 'tbl_kota_has_artefak';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_kota',
        'id_artefak',
        'file_pengumpulan',
        'teks_pengumpulan', // Pastikan teks_pengumpulan ada di sini
        'waktu_pengumpulan',
        'kategori',
        'prodi',
        'abstract', // Field untuk teks/abstrak
        'created_at',
        'updated_at',
    ];

    // Relasi ke ArtefakModel
    public function artefak()
    {
        return $this->belongsTo(ArtefakModel::class, 'id_artefak', 'id_artefak');
    }

    // Relasi ke KotaModel (jika ada)
    public function kota()
    {
        return $this->belongsTo(KotaModel::class, 'id_kota', 'id_kota');
    }
}
