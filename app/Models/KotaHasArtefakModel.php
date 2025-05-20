<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasArtefakModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_kota_has_artefak';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_kota', 
        'id_artefak',
        'file_pengumpulan',
        'waktu_pengumpulan'
    ];
    
    public function kota()
    {
        return $this->belongsTo(KotaModel::class, 'id_kota', 'id_kota');
    }
    
    public function artefak()
    {
        return $this->belongsTo(ArtefakModel::class, 'id_artefak', 'id_artefak');
    }
}