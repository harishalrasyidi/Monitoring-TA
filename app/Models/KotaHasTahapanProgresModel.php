<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasTahapanProgresModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_kota_has_tahapan_progres';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_kota',
        'id_master_tahapan_progres',
        'status', 
    ];
}
