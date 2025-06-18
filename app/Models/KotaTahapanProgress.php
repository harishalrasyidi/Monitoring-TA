<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KotaTahapanProgress extends Model
{
    protected $table = 'tbl_kota_has_tahapan_progres';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_kota',
        'id_master_tahapan_progres',
        'status',
        'created_at',
        'updated_at'
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function masterTahapan()
    {
        return $this->belongsTo(MasterTahapanProgress::class, 'id_master_tahapan_progres', 'id');
    }
} 