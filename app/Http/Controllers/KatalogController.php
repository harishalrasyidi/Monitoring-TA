<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KotaHasArtefakModel;
use App\Models\ArtefakModel;
use App\Models\KotaModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // Set the id for Proposal Tugas Akhir
        $proposal_id = 7; // Update this with the correct ID for "Proposal Tugas Akhir"

        // Get all data for dropdown filter options
        $years = KotaModel::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
        $kbks = KotaModel::select('kbk')->whereNotNull('kbk')->distinct()->get();
        $topics = KotaModel::select('topik')->whereNotNull('topik')->distinct()->get();
        $jenis_tas = [
            ['id' => 'analisis', 'name' => 'Analisis'],
            ['id' => 'development', 'name' => 'Development']
        ];
        $metodologis = KotaModel::select('metodologi')->whereNotNull('metodologi')
                                ->where('jenis_ta', 'development')
                                ->distinct()->get();
        $prodis = [
            ['id' => '1', 'name' => 'D3 TI A'],
            ['id' => '2', 'name' => 'D3 TI B'],
            ['id' => '3', 'name' => 'D4 TI A'],
            ['id' => '4', 'name' => 'D4 TI B']
        ];
        
        // Get all dosen for filter
        $dosens = User::where('role', 2)->get();

        // Query dasar untuk mendapatkan Proposal Tugas Akhir
        $query = KotaHasArtefakModel::query()
            ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
            ->join('tbl_kota', 'tbl_kota_has_artefak.id_kota', '=', 'tbl_kota.id_kota')
            ->leftJoin('tbl_kota_has_user', function($join) {
                $join->on('tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
                    ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                    ->where('users.role', '=', 2); // Join with dosen (role 2)
            })
            ->select('tbl_kota_has_artefak.*', 'tbl_artefak.nama_artefak', 'tbl_artefak.deskripsi', 
                    'tbl_kota.nama_kota', 'tbl_kota.judul', 'tbl_kota.kelas', 'tbl_kota.periode',
                    'tbl_kota.prodi', 'tbl_kota.kbk', 'tbl_kota.topik', 'tbl_kota.tahun', 
                    'tbl_kota.jenis_ta', 'tbl_kota.metodologi',
                    'users.name as dosen_name', 'users.id as dosen_id')
            ->where('tbl_kota_has_artefak.id_artefak', $proposal_id);
    
        // Filter berdasarkan KBK
        if ($request->filled('kbk')) {
            $query->where('tbl_kota.kbk', $request->kbk);
        }
    
        // Filter berdasarkan topik
        if ($request->filled('topik')) {
            $query->where('tbl_kota.topik', $request->topik);
        }
    
        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->where('tbl_kota.tahun', $request->tahun);
        }
        
        // Filter berdasarkan jenis TA
        if ($request->filled('jenis_ta')) {
            $query->where('tbl_kota.jenis_ta', $request->jenis_ta);
            
            // Jika development, tambahkan filter metodologi
            if ($request->jenis_ta == 'development' && $request->filled('metodologi')) {
                $query->where('tbl_kota.metodologi', $request->metodologi);
            }
        }
        
        // Filter berdasarkan prodi
        if ($request->filled('prodi')) {
            $query->where('tbl_kota.prodi', $request->prodi);
        }
        
        // Filter berdasarkan dosen pembimbing
        if ($request->filled('dosen_id')) {
            $query->where('users.id', $request->dosen_id);
        }
    
        // Dapatkan hasil dengan pagination
        $katalog = $query->paginate(12);
    
        return view('katalog.katalog', compact(
            'katalog', 
            'years', 
            'kbks', 
            'topics', 
            'jenis_tas', 
            'metodologis',
            'prodis',
            'dosens'
        ));
    }
}