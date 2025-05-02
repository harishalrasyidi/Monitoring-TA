<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResumeBimbinganModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_resume_bimbingan';
    protected $primaryKey = 'id_resume_bimbingan';
    protected $fillable = [
        'tanggal_bimbingan',
        'jam_mulai',
        'jam_selesai',
        'isi_resume_bimbingan',
        'isi_revisi_bimbingan',
        'tahapan_progres',
        'sesi_bimbingan',
    ];

    public function getResume($id = null)
    {
        if ($id === null) {
            return DB::table($this->table)->get();
        } else {
            return DB::table($this->table)->where('id_resume_bimbingan', $id)->first();
        }
    }

     // Accessor untuk format jam_mulai
    public function getJamMulaiAttribute($value)
    {
        return date('H:i', strtotime($value));
    }

     // Accessor untuk format jam_selesai
    public function getJamSelesaiAttribute($value)
    {
        return date('H:i', strtotime($value));
    }
}
