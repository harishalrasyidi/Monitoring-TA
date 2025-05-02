<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterArterfakModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_master_artefak';

    protected $fillable = [
        'nama_artefak',
    ];
}
