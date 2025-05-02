<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKegiatanHasTimelineModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tbl_kegiatan_has_timeline';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_timeline',
        'id_jadwal_kegiatan',
    ];
}
