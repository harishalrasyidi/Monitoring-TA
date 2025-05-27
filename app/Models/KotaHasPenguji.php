<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasPenguji extends Model
{
    use HasFactory;

    protected $table = 'tbl_kota_has_penguji';
    protected $guarded = [];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
} 