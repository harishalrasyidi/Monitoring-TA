<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'tbl_kota';
    protected $primaryKey = 'id_kota';
    public $timestamps = true;

    protected $fillable = [
        'nama_kota',
        'judul',
        'kelas',
        'periode'
    ];

    public function tahapanProgress()
    {
        return $this->hasMany(KotaTahapanProgress::class, 'id_kota', 'id_kota');
    }
} 