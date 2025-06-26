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
        'nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai', 
        'deskripsi', 
    ];

    public function artefak()
    {
        return $this->hasMany(TimelineHasArtefakModel::class, 'id_timeline', 'id_timeline');
    }

}
