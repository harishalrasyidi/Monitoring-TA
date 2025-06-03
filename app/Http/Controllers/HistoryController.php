<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KotaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the history of KoTA for pembimbing (role 2)
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Only allow role 2 (pembimbing) to access this
        if ($user->role != 2) {
            abort(403, 'Unauthorized access');
        }

        // Query untuk KoTA yang dibimbing
        $queryKota = KotaModel::query()
            ->join('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
            ->leftJoin('tbl_kota_has_tahapan_progres', 'tbl_kota.id_kota', '=', 'tbl_kota_has_tahapan_progres.id_kota')
            ->leftJoin('tbl_master_tahapan_progres', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres', '=', 'tbl_master_tahapan_progres.id')
            ->where('tbl_kota_has_user.id_user', $user->id)
            ->select('tbl_kota.*', 'tbl_master_tahapan_progres.nama_progres AS nama_tahapan')
            ->where(function ($query) {
                $query->where('tbl_kota_has_tahapan_progres.status', 'on_progres')
                    ->orWhere('tbl_kota_has_tahapan_progres.status', 'disetujui');
            });

        // Query untuk KoTA yang diuji
        $queryKotaDiuji = KotaModel::query()
            ->join('tbl_kota_has_penguji', 'tbl_kota.id_kota', '=', 'tbl_kota_has_penguji.id_kota')
            ->leftJoin('tbl_kota_has_tahapan_progres', 'tbl_kota.id_kota', '=', 'tbl_kota_has_tahapan_progres.id_kota')
            ->leftJoin('tbl_master_tahapan_progres', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres', '=', 'tbl_master_tahapan_progres.id')
            ->where('tbl_kota_has_penguji.id_user', $user->id)
            ->select('tbl_kota.*', 'tbl_master_tahapan_progres.nama_progres AS nama_tahapan')
            ->where(function ($query) {
                $query->where('tbl_kota_has_tahapan_progres.status', 'on_progres')
                    ->orWhere('tbl_kota_has_tahapan_progres.status', 'disetujui');
            });

            // Ambil data tahapan yang sesuai dengan role pembimbing dan penguji
            $tahapanProgres = DB::table('tbl_master_tahapan_progres')
                ->join('tbl_kota_has_tahapan_progres', 'tbl_master_tahapan_progres.id', '=', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres')
                ->where(function($query) use ($user) {
                    $query->whereExists(function($subquery) use ($user) {
                        $subquery->select(DB::raw(1))
                            ->from('tbl_kota_has_user')
                            ->whereRaw('tbl_kota_has_user.id_kota = tbl_kota_has_tahapan_progres.id_kota')
                            ->where('tbl_kota_has_user.id_user', $user->id);
                    })
                    ->orWhereExists(function($subquery) use ($user) {
                        $subquery->select(DB::raw(1))
                            ->from('tbl_kota_has_penguji')
                            ->whereRaw('tbl_kota_has_penguji.id_kota = tbl_kota_has_tahapan_progres.id_kota')
                            ->where('tbl_kota_has_penguji.id_user', $user->id);
                    });
                })
                ->select('tbl_master_tahapan_progres.*')
                ->distinct()
                ->get();

        // Filter berdasarkan parameter
        if ($request->has('sort') && $request->has('value')) {
            $sort = $request->input('sort');
            $value = $request->input('value');
            $queryKota->where($sort, $value);
            $queryKotaDiuji->where($sort, $value);
        }

        // Sorting
        if ($request->has('sort') && $request->has('direction')) {
            $queryKota->orderBy($request->input('sort'), $request->input('direction'));
            $queryKotaDiuji->orderBy($request->input('sort'), $request->input('direction'));
        }

        $kotas = $queryKota->paginate(10);
        $kotas_diuji = $queryKotaDiuji->paginate(10);

        $availableYears = DB::table('tbl_kota')
            ->join('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
            ->where('tbl_kota_has_user.id_user', Auth::id())
            ->distinct()
            ->orderBy('tbl_kota.periode', 'desc')
            ->pluck('tbl_kota.periode');

        return view('history.pembimbing.index', compact('kotas', 'kotas_diuji', 'availableYears', 'tahapanProgres'));
    }
}