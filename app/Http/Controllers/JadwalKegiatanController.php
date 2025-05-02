<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalKegiatanModel;
use App\Models\NamaKegiatanModel;
use App\Models\TimelineModel;
use App\Models\KotaHasJadwalKegiatanModel;
use App\Models\KotaHasMetodologiModel;
use App\Models\JadwalKegiatanHasTimelineModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class JadwalKegiatanController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $user = auth()->user();

        // Mendapatkan id_kota dari user yang sedang login
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        // Mengambil data dari tbl_kota_has_jadwal_kegiatan berdasarkan id_kota
        $kota_jadwal_kegiatan = DB::table('tbl_kota_has_jadwal_kegiatan')
                                ->where('id_kota', $id_kota)
                                ->get();

        // Mengambil id_jadwal_kegiatan dari data yang diambil
        $id_jadwal_kegiatan = $kota_jadwal_kegiatan->pluck('id_jadwal_kegiatan');

        // Mengambil detail jadwal kegiatan dari tbl_jadwal_kegiatan
        $events = DB::table('tbl_jadwal_kegiatan')
                    ->whereIn('id', $id_jadwal_kegiatan)
                    ->get();

        // Mengambil resourceId dari jadwal kegiatan yang diambil
        $id_nama_kegiatan = $events->pluck('id_nama_kegiatan');

        // Mengambil nama kegiatan dari tbl_nama_kegiatan
        $resource = DB::table('tbl_nama_kegiatan')
                    ->whereIn('id', $id_nama_kegiatan)
                    ->get();

        $data = [
            'events' => $events,
            'resource' => $resource
        ];

        $status = DB::table('tbl_nama_kegiatan')
                    ->whereIn('id', function ($query) use ($id_jadwal_kegiatan) {
                        $query->select('id_nama_kegiatan')
                            ->from('tbl_jadwal_kegiatan')
                            ->whereIn('id', $id_jadwal_kegiatan)
                            ->where('status', 'pending');
                    })
                    ->get();

        $tahun = date('Y'); // atau bisa diganti dengan tahun yang diinginkan
        $tahapan_progres = DB::table('tbl_timeline')
                            ->whereYear('tanggal_mulai', $tahun)
                            ->get();

        $metodologi = DB::table('tbl_master_metodologi')
                    ->select('id', 'nama_metodologi')
                    ->get();

        $kota_metodologi = KotaHasMetodologiModel::where('id_kota', $id_kota)
                        ->join('tbl_master_metodologi', 'tbl_kota_has_metodologi.id_metodologi', '=', 'tbl_master_metodologi.id')
                        ->select('tbl_kota_has_metodologi.id', 'tbl_master_metodologi.nama_metodologi')
                        ->get();

        return view('kegiatan.index', compact('data', 'tahapan_progres', 'metodologi', 'kota_metodologi', 'status'));

    }

    public function store_kegiatan(Request $request) {

        $request->validate([
            'nama_kegiatan' => 'required',
            // 'jenis_label' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'id_timeline' => 'required',
        ]);

        $nama_kegiatan = NamaKegiatanModel::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            // 'jenis_label' => $request->jenis_label,
        ]);

        $user = auth()->user();
        
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        $id_nama_kegiatan = $nama_kegiatan->id;

        // dd($id_nama_kegiatan);

        if ($id_nama_kegiatan) {
            $jadwal_kegiatan = JadwalKegiatanModel::create([
                'id_nama_kegiatan' => $id_nama_kegiatan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);
        }

        $id_jadwal_kegiatan = $jadwal_kegiatan->id;

        if ($id_kota) {
            KotaHasJadwalKegiatanModel::create([
                'id_kota' => $id_kota,
                'id_jadwal_kegiatan' => $id_jadwal_kegiatan,
            ]);
            JadwalKegiatanHasTimelineModel::create([
                'id_timeline' => $request->id_timeline,
                'id_jadwal_kegiatan' => $id_jadwal_kegiatan,
            ]);
        }

        return redirect()->route('kegiatan.index')->with('success', 'Jadwal Kegiatan berhasil disimpan');

    }

    public function store_metodologi(Request $request){
        $user = auth()->user();

        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');
        
        $request->validate([
            'id_metodologi' => 'required|exists:tbl_master_metodologi,id',
        ]);
        
        if ($id_kota) {
            // Cek apakah id_kota dan id_metodologi sudah ada di tbl_kota_has_metodologi
            $existingRecord = KotaHasMetodologiModel::where('id_kota', $id_kota)
                                ->first();
        
            if ($existingRecord) {
                // Jika sudah ada, update id_metodologi
                $existingRecord->update([
                    'id_metodologi' => $request->id_metodologi,
                ]);
            } else {
                // Jika belum ada, tambahkan baru
                KotaHasMetodologiModel::create([
                    'id_kota' => $id_kota,
                    'id_metodologi' => $request->id_metodologi,
                ]);
            }
        }
        
        return redirect()->route('kegiatan.index')->with('success', 'Metodologi berhasil disimpan');       
       
    }


    public function update_metodologi(Request $request, $id)
    {
        $request->validate([
            'id_metodologi' => 'required|exists:tbl_master_metodologi,id',
        ]);

        $kotaMetodologi = KotaHasMetodologiModel::findOrFail($id);
        $kotaMetodologi->update([
            'id_metodologi' => $request->id_metodologi,
        ]);


        return redirect()->route('kegiatan.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function store_status_kegiatan(Request $request)
    {
        
        $id_nama_kegiatan = $request->input('id');
        $jadwalKegiatan = JadwalKegiatanModel::where('id_nama_kegiatan', $id_nama_kegiatan)->firstOrFail();
        
        // Update status kegiatan
        $jadwalKegiatan->status = $request->input('status');

        if ($request->input('status') == 'completed') {
            $jadwalKegiatan->tanggal_selesai = Carbon::now();
        } else {
            $jadwalKegiatan->tanggal_selesai = null; // atau bisa diisi dengan logika lain
        }
        

        $jadwalKegiatan->save();
       
        return redirect()->route('kegiatan.index');
    }

    public function edit_Kegiatan(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'id' => 'required|exists:tbl_jadwal_kegiatan,id',
            'group' => 'required|exists:tbl_nama_kegiatan,id',
            // 'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date',
            'nama' => 'required',
        ]);
        // dd($validated['group']);
        // Temukan event berdasarkan ID
        $event = JadwalKegiatanModel::find($validated['id']);
        
        // Update data event
        // $event->title = $validated['title'];
        $event->tanggal_mulai = $validated['start'];
        $event->tanggal_selesai = $validated['end'];
        $event->save();

        // Update data nama kegiatan di tbl_nama_kegiatan
        $namaKegiatan = NamaKegiatanModel::find($validated['group']); // Ambil entri di tbl_nama_kegiatan berdasarkan ID yang sama

        if ($namaKegiatan) {
            $namaKegiatan->nama_kegiatan = $validated['nama'];
            $namaKegiatan->save();
        } else {
            return redirect()->route('kegiatan.index')->with('error', 'Nama Kegiatan tidak ada');
        }

        // Temukan resource berdasarkan ID
        $resource = NamaKegiatanModel::find($validated['id']);
        
        // Update data resource
        $resource->nama_kegiatan = $validated['nama'];

        $resource->save();
        
        return redirect()->route('kegiatan.index')->with('success', 'Data kegiatan berhasil diperbarui.');

    }

    public function destroy(Request $request)
    {
        // dd($request);
        try {
            $itemId = $request->input('id');
            // Temukan event berdasarkan ID
            $event = JadwalKegiatanModel::findOrFail($itemId);

            if ($event->id_nama_kegiatan) {
                $resource = NamaKegiatanModel::findOrFail($event->id_nama_kegiatan);
                $resource->delete();
            }
            // Hapus event
            $event->delete();
            

            return redirect()->route('kegiatan.index');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete event and related resource. Error: ' . $e->getMessage()], 500);
        }
    }

    public function detail($id)
    {
        $kota_jadwal_kegiatan = DB::table('tbl_kota_has_jadwal_kegiatan')
                                ->where('id_kota', $id)
                                ->get();

        // Mengambil id_jadwal_kegiatan dari data yang diambil
        $id_jadwal_kegiatan = $kota_jadwal_kegiatan->pluck('id_jadwal_kegiatan');

        // Mengambil detail jadwal kegiatan dari tbl_jadwal_kegiatan
        $events = DB::table('tbl_jadwal_kegiatan')
                    ->whereIn('id', $id_jadwal_kegiatan)
                    ->get();

        // Mengambil resourceId dari jadwal kegiatan yang diambil
        $id_nama_kegiatan = $events->pluck('id_nama_kegiatan');

        // Mengambil nama kegiatan dari tbl_nama_kegiatan
        $resource = DB::table('tbl_nama_kegiatan')
                    ->whereIn('id', $id_nama_kegiatan)
                    ->get();

        $data = [
            'events' => $events,
            'resource' => $resource
        ];

        $kota_metodologi = KotaHasMetodologiModel::where('id_kota', $id)
        ->join('tbl_master_metodologi', 'tbl_kota_has_metodologi.id_metodologi', '=', 'tbl_master_metodologi.id')
        ->select('tbl_kota_has_metodologi.id', 'tbl_master_metodologi.nama_metodologi')
        ->get();

        return view('kegiatan.detail', compact('data','kota_metodologi'));
    }

    // public function storeNamaKegiatan(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'type' => 'required'
    //     ]);
        
    //     $namaKegiatan = NamaKegiatanModel::create([
    //         'title' => $request->title,
    //         'type' => $request->type,
            
    //     ]);

    //     // Simpan ID ke session
    //     $request->session()->put('resourceId', $namaKegiatan->id);

    //     return redirect()->route('kegiatan.index')->with('step', 2);
    // }

    // public function storeJadwalKegiatan(Request $request)
    // {
    //     $user = auth()->user();
    
    //     // Mendapatkan id_kota dari user yang sedang login
    //     $id_kota = DB::table('tbl_kota_has_user')
    //                 ->where('id_user', $user->id)
    //                 ->value('id_kota');
        
    //     $id_tahapan_progres = DB::table('tbl_master_tahapan_progres')
    //                 ->get()
    //                 ->value('id');

    //     $request->validate([
    //         'start' => 'required|date',
    //         'end' => 'required|date',
    //         'id' => 'required',
    //     ]);

    //     $namaKegiatanId = $request->session()->get('resourceId');
       
    //     $jadwalKegiatan = JadwalKegiatanModel::create([
    //         'resourceId' => $namaKegiatanId,
    //         'start' => $request->start,
    //         'end' => $request->end,
    //     ]);

    //     $id_jadwal_kegiatan = $jadwalKegiatan->id;

    //     if ($id_kota) {
    //         KotaHasJadwalKegiatanModel::create([
    //             'id_kota' => $id_kota,
    //             'id_jadwal_kegiatan' => $id_jadwal_kegiatan,
    //         ]);
    //         JadwalKegiatanHasTimelineModel::create([
    //             'id_timeline' => $request->id,
    //             'id_jadwal_kegiatan' => $id_jadwal_kegiatan,
    //         ]);
    //     }

    
    //     // Hapus ID dari session
    //     $request->session()->forget('resourceId');

    //     return redirect()->route('kegiatan.index')->with('success', 'Jadwal Kegiatan berhasil disimpan');
    // }

    // public function edit_resource(Request $request)
    // {
    //     // Validasi data
    //     $validated = $request->validate([
    //         'id' => 'required|exists:tbl_nama_kegiatan,id',
    //         'title' => 'required|string|max:255',
    //     ]);

    //     // Temukan resource berdasarkan ID
    //     $resource = NamaKegiatanModel::find($validated['id']);
        
    //     // Update data resource
    //     $resource->nama_kegiatan = $validated['title'];

    //     $resource->save();
        
    //     return redirect()->route('kegiatan.index');
    // }
}
