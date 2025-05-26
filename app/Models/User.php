<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role',
        'nomor_induk',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi many-to-many dengan KotaModel melalui tbl_kota_has_user
     */
    public function kota()
    {
        return $this->belongsToMany(KotaModel::class, 'tbl_kota_has_user', 'id_user', 'id_kota');
    }
    
    /**
     * Relasi hasMany dengan KotaHasUserModel
     */
    public function kotaUsers()
    {
        return $this->hasMany(KotaHasUserModel::class, 'id_user', 'id');
    }
    
    /**
     * Get kota aktif untuk user ini (yang sedang on_progres)
     */
    public function getActiveKota()
    {
        return $this->kota()
                    ->whereHas('tahapanProgres', function($q) {
                        $q->where('status', 'on_progres');
                    })
                    ->first();
    }
    
    /**
     * Check apakah user adalah mahasiswa
     */
    public function isMahasiswa()
    {
        return $this->role == 3;
    }
    
    /**
     * Check apakah user adalah dosen
     */
    public function isDosen()
    {
        return $this->role == 2;
    }
    
    /**
     * Check apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role == 1;
    }
    
    /**
     * Check apakah user adalah anggota dari kota tertentu
     */
    public function isAnggotaKota($kotaId)
    {
        return $this->kota()->where('id_kota', $kotaId)->exists();
    }
    
    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
    
    /**
     * Scope untuk mahasiswa saja
     */
    public function scopeMahasiswa($query)
    {
        return $query->where('role', 3);
    }
    
    /**
     * Scope untuk dosen saja
     */
    public function scopeDosen($query)
    {
        return $query->where('role', 2);
    }
    
    /**
     * Helper method untuk mengecek role - lebih fleksibel
     */
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        
        return $this->role == $role;
    }
}