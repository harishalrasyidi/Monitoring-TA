<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelineModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_timeline';
    protected $primaryKey = 'id_timeline';
    protected $fillable = [
        'id_kota',
        'id_master_tahapan_progres',
        'nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai', 
        'deskripsi', 
    ];

    public function artefak()
    {
        return $this->hasMany(TimelineHasArtefakModel::class, 'id_timeline', 'id_timeline');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function masterTahapan()
    {
        return $this->belongsTo(MasterTahapanProgress::class, 'id_master_tahapan_progres', 'id');
    }

}
