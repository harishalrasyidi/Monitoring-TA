<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YudisiumLogModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_yudisium_log';
    protected $primaryKey = 'id_log';
    protected $fillable = [
        'id_yudisium',
        'id_user',
        'jenis_perubahan',
        'nilai_lama',
        'nilai_baru',
        'waktu_perubahan',
        'keterangan',
    ];

    /**
     * Get the yudisium that owns the log.
     */
    public function yudisium()
    {
        return $this->belongsTo(YudisiumModel::class, 'id_yudisium', 'id_yudisium');
    }

    /**
     * Get the user who made the change.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}