<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKesediaanPengujiModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_jadwal_kesediaan_penguji';
    protected $primaryKey = 'id_jadwal';
    protected $fillable = [
        'nama_penguji',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nama_penguji', 'name')
                    ->where('role', 2);
    }
}
