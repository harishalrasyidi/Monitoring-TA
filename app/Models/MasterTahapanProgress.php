<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTahapanProgress extends Model
{
    protected $table = 'tbl_master_tahapan_progres';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_progres'
    ];

    public function kotaTahapanProgress()
    {
        return $this->hasMany(KotaTahapanProgress::class, 'id_master_tahapan_progres', 'id');
    }
} 