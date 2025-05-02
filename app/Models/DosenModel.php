<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_dosen';

    protected $fillable = [
        'nip', 'nama', 'email',
    ];
}
