<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class YudisiumExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $periode;
    protected $kelas;
    protected $kategori;
    protected $status;

    /**
     * Create a new export instance.
     *
     * @param  int|null  $periode
     * @param  string|null  $kelas
     * @param  int|null  $kategori
     * @param  string|null  $status
     * @return void
     */
    public function __construct($periode = null, $kelas = null, $kategori = null, $status = null)
    {
        $this->periode = $periode;
        $this->kelas = $kelas;
        $this->kategori = $kategori;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->leftJoin('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
            ->leftJoin('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
            ->select(
                'tbl_yudisium.id_yudisium',
                'tbl_kota.nama_kota',
                'tbl_kota.judul',
                'tbl_kota.kelas',
                'tbl_kota.periode',
                'tbl_yudisium.kategori_yudisium',
                'tbl_yudisium.tanggal_yudisium',
                'tbl_yudisium.nilai_akhir',
                'tbl_yudisium.status',
                'tbl_yudisium.keterangan',
                'users.name as nama_user',
                'users.role'
            );

        // Terapkan filter
        if ($this->periode) {
            $query->where('tbl_kota.periode', $this->periode);
        }
        if ($this->kelas) {
            $query->where('tbl_kota.kelas', $this->kelas);
        }
        if ($this->kategori) {
            $query->where('tbl_yudisium.kategori_yudisium', $this->kategori);
        }
        if ($this->status) {
            $query->where('tbl_yudisium.status', $this->status);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No.',
            'KoTA',
            'Judul TA',
            'Kelas',
            'Periode',
            'Kategori Yudisium',
            'Tanggal Yudisium',
            'Nilai Akhir',
            'Status',
            'Keterangan',
            'Mahasiswa',
            'Dosen Pembimbing'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        // Ambil nama mahasiswa
        $mahasiswa = DB::table('tbl_kota_has_user')
            ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
            ->where('tbl_kota_has_user.id_kota', $row->id_kota)
            ->where('users.role', 3)
            ->pluck('users.name')
            ->join(', ');

        // Ambil nama dosen pembimbing
        $dosen = DB::table('tbl_kota_has_user')
            ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
            ->where('tbl_kota_has_user.id_kota', $row->id_kota)
            ->where('users.role', 2)
            ->pluck('users.name')
            ->join(', ');

        return [
            $row->id_yudisium,
            $row->nama_kota,
            $row->judul,
            $row->kelas,
            $row->periode,
            'Yudisium ' . $row->kategori_yudisium,
            $row->tanggal_yudisium,
            $row->nilai_akhir,
            ucfirst($row->status),
            $row->keterangan,
            $mahasiswa,
            $dosen
        ];
    }
}