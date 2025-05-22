<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class YudisiumModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_yudisium';
    protected $primaryKey = 'id_yudisium';
    protected $fillable = [
        'id_kota',
        'kategori_yudisium',
        'tanggal_yudisium',
        'nilai_akhir',
        'status',
        'keterangan',
    ];

    /**
     * Get the Kota associated with the yudisium.
     */
    public function kota()
    {
        return $this->belongsTo(KotaModel::class, 'id_kota', 'id_kota');
    }

    /**
     * Get the logs for the yudisium.
     */
    public function logs()
    {
        return $this->hasMany(YudisiumLogModel::class, 'id_yudisium', 'id_yudisium');
    }

    /**
     * Get yudisium data by ID.
     *
     * @param int|null $id
     * @return \Illuminate\Support\Collection|\stdClass
     */
    public function getYudisium($id = null)
    {
        if ($id === null) {
            return DB::table($this->table)->get();
        } else {
            return DB::table($this->table)->where('id_yudisium', $id)->first();
        }
    }

    /**
     * Get distribution of yudisium categories.
     *
     * @param int|null $periode
     * @param string|null $kelas
     * @return \Illuminate\Support\Collection
     */
    public function getDistribusiYudisium($periode = null, $kelas = null)
    {
        $query = DB::table($this->table)
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->select('tbl_yudisium.kategori_yudisium', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('tbl_yudisium.kategori_yudisium');
        
        if ($periode) {
            $query->where('tbl_kota.periode', $periode);
        }
        
        if ($kelas) {
            $query->where('tbl_kota.kelas', $kelas);
        }
        
        return $query->get();
    }
}