<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaTA extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_anggota_ta';
    
    protected $fillable = [
        'id_katalog_ta',
        'id_user',
    ];
    
    // Relasi ke katalog TA
    public function katalogTA()
    {
        return $this->belongsTo(KatalogTA::class, 'id_katalog_ta', 'id');
    }
    
    // Relasi ke pengguna
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}