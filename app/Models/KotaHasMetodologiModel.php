<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasMetodologiModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tbl_kota_has_metodologi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_kota',
        'id_metodologi',
    ];

}
