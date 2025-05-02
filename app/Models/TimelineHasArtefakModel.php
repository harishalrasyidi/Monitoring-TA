<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelineHasArtefakModel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tbl_timeline_has_artefak';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_timeline',
        'id_master_artefak',
    ];

    public function masterArtefak()
    {
        return $this->belongsTo(MasterArterfakModel::class, 'id_master_artefak');
    }
}
