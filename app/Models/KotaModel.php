<?php

// File: App/Models/KotaModel.php
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

    /**
     * Relasi many-to-many dengan User melalui tbl_kota_has_user
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'tbl_kota_has_user', 'id_kota', 'id_user');
    }
    
    /**
     * Relasi untuk mendapatkan mahasiswa saja (role = 3)
     */
    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'tbl_kota_has_user', 'id_kota', 'id_user')
                    ->where('role', 3);
    }
    
    /**
     * Relasi untuk mendapatkan dosen saja (role = 2)
     */
    public function dosen()
    {
        return $this->belongsToMany(User::class, 'tbl_kota_has_user', 'id_kota', 'id_user')
                    ->where('role', 2);
    }
    
    /**
     * Relasi dengan tahapan progres
     */
    public function tahapanProgres()
    {
        return $this->hasMany(KotaHasTahapanProgresModel::class, 'id_kota', 'id_kota');
    }
    
    /**
     * Relasi dengan artefak
     */
    public function artefak()
    {
        return $this->hasMany(KotaHasArtefakModel::class, 'id_kota', 'id_kota');
    }
    
    /**
     * Get current tahapan progres yang sedang aktif
     */
    public function getCurrentTahapan()
    {
        return $this->tahapanProgres()
                    ->join('tbl_master_tahapan_progres', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres', '=', 'tbl_master_tahapan_progres.id')
                    ->where('status', 'on_progres')
                    ->select('tbl_master_tahapan_progres.nama_progres', 'tbl_kota_has_tahapan_progres.status')
                    ->first();
    }
    
    /**
     * Check apakah kota sudah selesai (tahapan 4 dengan status selesai)
     */
    public function isCompleted()
    {
        return $this->tahapanProgres()
                    ->where('id_master_tahapan_progres', 4)
                    ->where('status', 'selesai')
                    ->exists();
    }
    
    /**
     * Get progress percentage berdasarkan tahapan yang sudah selesai
     */
    public function getProgressPercentage()
    {
        $completedStages = $this->tahapanProgres()
                               ->where('status', 'selesai')
                               ->count();
        
        return ($completedStages / 4) * 100; // Assuming 4 total stages
    }
    
    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }
    
    /**
     * Scope untuk filter berdasarkan kelas
     */
    public function scopeByKelas($query, $kelas)
    {
        return $query->where('kelas', $kelas);
    }
    
    /**
     * Scope untuk kota yang aktif (ada tahapan on_progres)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('tahapanProgres', function($q) {
            $q->where('status', 'on_progres');
        });
    }
    
    /**
     * Legacy method - tetap dipertahankan untuk backward compatibility
     */
    public function getKota($id = null)
    {
        if ($id === null) {
            return DB::table($this->table)->get();
        } else {
            return DB::table($this->table)->where('id_kota', $id)->first();
        }
    }
}
