<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ArtefakModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_artefak';
    protected $primaryKey = 'id_artefak';
    protected $fillable = [
        'id_artefak',
        'nama_artefak',
        'deskripsi',
        'kategori_artefak',
        'tenggat_waktu',
    ];

    public function getArtefak($id = null)
    {
        if ($id === null) {
            return DB::table($this->table)->get();
        } else {
            return DB::table($this->table)->where('id_artefak', $id)->first();
        }
    }
}
