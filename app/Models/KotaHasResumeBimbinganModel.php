<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasResumeBimbinganModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_kota_has_resume_bimbingan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_kota',
        'id_user',
        'id_resume_bimbingan', 
    ];
}
