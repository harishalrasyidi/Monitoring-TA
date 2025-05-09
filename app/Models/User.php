<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role',
        'nomor_induk',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function kota()
    {
        return $this->belongsTo(KotaModel::class, 'id_kota');
    }

// Ganti nama fungsi agar sesuai konvensi Laravel
public function kotaUsers()
{
    return $this->hasMany(KotaHasUserModel::class, 'id_user', 'id');
}

    
    // Helper method untuk mengecek apakah user adalah penulis dari katalog TA tertentu
    public function isPenulisTA($katalogTAId)
    {
        return $this->anggotaTA()->where('id_katalog_ta', $katalogTAId)->exists();
    }

    public function anggotaTA()
    {
    return $this->hasMany(AnggotaTA::class, 'id_user', 'id');
    }


    public function katalogTA()
    {
        return $this->belongsToMany(KatalogTA::class, 'tbl_kota_has_user', 'id_user', 'id_katalog');
    }
    
}
