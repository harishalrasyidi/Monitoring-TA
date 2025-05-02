<?php

namespace App\Http\Controllers;
use App\Models\TimelineModel;
use App\Models\TimelineHasArtefakModel;
use App\Models\MasterArterfakModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\EventModel;
use Illuminate\Support\Facades\Storage;

class TimelineController extends Controller
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
        $timelines = TimelineModel::orderBy('tanggal_mulai', 'asc')->get();
        $masterArtefaks = MasterArterfakModel::all();

        return view('timeline.index', compact('timelines', 'masterArtefaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'deskripsi' => 'required',
            'id_master_artefak' => 'required|array', // validasi array karena multiple select
            'id_master_artefak.*' => 'exists:tbl_master_artefak,id' // validasi keberadaan id di tbl_master_artefak
        ]);

        $existingTimeline = DB::table('tbl_timeline')->where('nama_kegiatan', $request->nama_kegiatan)->exists();
        if($existingTimeline) {
            session()->flash('error', 'Nomor Timeline sudah terdaftar');
            return redirect()->back()->withInput();
        }

        // Simpan data timeline
        $timeline = TimelineModel::create($request->only(['nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi']));

        // Simpan data ke tbl_timeline_has_artefak
        foreach ($request->id_master_artefak as $id_artefak) {
            TimelineHasArtefakModel::create([
                'id_timeline' => $timeline->id_timeline,
                'id_master_artefak' => $id_artefak,
            ]);
        }

        session()->flash('success', 'Data timeline berhasil ditambahkan');

        return redirect()->route('timeline');
    }

    public function detail($id)
    {
        $timelines = TimelineModel::findOrFail($id);

        $nama_kegiatan = '';
        if ($timelines->nama_kegiatan == 1) {
            $nama_kegiatan = "Seminar 1";
        } elseif ($timelines->nama_kegiatan == 2) {
            $nama_kegiatan = "Seminar 2";
        } elseif ($timelines->nama_kegiatan == 3) {
            $nama_kegiatan = "Seminar 3";
        } elseif ($timelines->nama_kegiatan == 4) {
            $nama_kegiatan = "Sidang";
        } else {
            $nama_kegiatan = "Unknown";
        }

        return view('timeline.detail', compact('timelines', 'nama_kegiatan'));
    }

    public function edit($id)
    {
        $timeline = TimelineModel::findOrFail($id);
        if (!$timeline) {
            return redirect()->route('timeline')->withErrors('Data tidak ditemukan.');
        }

        $nama_kegiatan = '';
        if ($timeline->nama_kegiatan == 1) {
            $nama_kegiatan = "Seminar 1";
        } elseif ($timeline->nama_kegiatan == 2) {
            $nama_kegiatan = "Seminar 2";
        } elseif ($timeline->nama_kegiatan == 3) {
            $nama_kegiatan = "Seminar 3";
        } elseif ($timeline->nama_kegiatan == 4) {
            $nama_kegiatan = "Sidang";
        } else {
            $nama_kegiatan = "Unknown";
        }

        return view('timeline.edit', compact('timeline', 'nama_kegiatan'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'deskripsi' => 'required',
            'id_master_artefak' => 'required|array', // validasi array karena multiple select
            'id_master_artefak.*' => 'exists:tbl_master_artefak,id' // validasi keberadaan id di tbl_master_artefak
        ]);

        $timeline = TimelineModel::findOrFail($id);

        // Update data timeline
        $timeline->update($request->only(['nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi']));

        // Hapus semua relasi artefak yang ada
        TimelineHasArtefakModel::where('id_timeline', $timeline->id_timeline)->delete();

        // Simpan kembali data ke tbl_timeline_has_artefak
        foreach ($request->id_master_artefak as $id_artefak) {
            TimelineHasArtefakModel::create([
                'id_timeline' => $timeline->id_timeline,
                'id_master_artefak' => $id_artefak,
            ]);
        }

        session()->flash('success', 'Data timeline berhasil dirubah');

        return redirect()->route('timeline');
    }

    public function destroy($id)
    {
        $timeline = TimelineModel::findOrFail($id);
        TimelineHasArtefakModel::where('id_master_artefak', $timeline->id_master_artefak)->delete();

        // Hapus timeline
        $timeline->delete();

        session()->flash('success', 'Data berhasil dihapus');

        return redirect()->route('timeline');
    }
}
