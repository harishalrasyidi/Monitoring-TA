<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaHasUserModel extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_kota_has_user';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_kota',
        'id_user',
        'id_katalog', // Jika masih dibutuhkan untuk transisi
    ];
    
    public $timestamps = false;
    
    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
    
    /**
     * Relasi dengan KotaModel
     */
    public function kota()
    {
        return $this->belongsTo(KotaModel::class, 'id_kota', 'id_kota');
    }
    
    /**
     * Scope untuk filter berdasarkan kota
     */
    public function scopeByKota($query, $kotaId)
    {
        return $query->where('id_kota', $kotaId);
    }
    
    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }
    
    /**
     * Scope untuk mahasiswa saja
     */
    public function scopeMahasiswa($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('role', 3);
        });
    }
    
    /**
     * Scope untuk dosen saja
     */
    public function scopeDosen($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('role', 2);
        });
    }
}