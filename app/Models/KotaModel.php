<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KotaModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_kota';
    protected $primaryKey = 'id_kota';
    protected $fillable = [
        'nama_kota',
        'judul',
        'kelas', 
        'periode', 
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tbl_kota_has_user', 'id_kota', 'id_user');
    }

    public function getKota($id = null)
    {
        if ($id === null) {
            return DB::table($this->table)->get();
        } else {
            return DB::table($this->table)->where('id_kota', $id)->first();
        }
    }

    /**
     * Get the yudisium associated with the kota.
     */
    public function yudisium()
    {
        return $this->hasOne(YudisiumModel::class, 'id_kota', 'id_kota');
    }

    public function resumeBimbingan()
{
    return $this->hasMany(KotaHasResumeBimbinganModel::class, 'id_kota');
}


    // Tambahan 
    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'tbl_kota_has_user', 'id_kota', 'id_user')
    //                 ->withPivot('role')
    //                 ->wherePivot('role', 3); // Opsional, filter role mahasiswa
    // }

    // public function dosens()
    // {
    //     return $this->belongsToMany(User::class, 'tbl_kota_has_user', 'id_kota', 'id_user')
    //         ->where('role', 1); // Dosen
    // }

    // public function tahapanProgress()
    // {
    //     return $this->hasMany(TahapanProgress::class, 'id_kota', 'id_kota');
    // }

    // public function resumeBimbingan()
    // {
    //     return $this->hasMany(KotaHasResumeBimbinganModel::class, 'id_kota', 'id_kota');
    // }
}
