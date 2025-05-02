<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasJadwalKegiatanModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_kota_has_jadwal_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_kota',
        'id_jadwal_kegiatan',
    ];

}
