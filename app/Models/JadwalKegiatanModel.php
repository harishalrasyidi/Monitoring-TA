<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKegiatanModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_jadwal_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function namaKegiatan()
    {
        return $this->belongsTo(NamaKegiatanModel::class, 'id_nama_kegiatan');
    }
}

