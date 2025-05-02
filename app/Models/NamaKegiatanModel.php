<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaKegiatanModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_nama_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_kegiatan',
        // 'jenis_label'
    ];
}

