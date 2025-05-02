<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ResumeBimbinganModel;
use App\Models\KotaHasResumeBimbinganModel; 
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ResumeBimbinganController extends Controller
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
    public function index(Request $request)
    {
        $user = auth()->user();
        $id_kota = DB::table('tbl_kota_has_user')
            ->where('id_user', $user->id)
            ->value('id_kota');

        // Membuat query untuk mengambil data dari tbl_resume_bimbingan
        $query = ResumeBimbinganModel::query();

        $query->join('tbl_kota_has_resume_bimbingan', 'tbl_resume_bimbingan.id_resume_bimbingan', '=', 'tbl_kota_has_resume_bimbingan.id_resume_bimbingan')
            ->where('tbl_kota_has_resume_bimbingan.id_kota', $id_kota)
            ->select('tbl_resume_bimbingan.*');
        
        // Menambahkan filter berdasarkan parameter 'sort' dan 'value'
        if ($request->has('sort') && $request->has('value')) {
            $sort = $request->input('sort');
            $value = $request->input('value');
            
            // Tambahkan filter berdasarkan nilai yang dipilih
            $query->where($sort, $value);
        }

        // Menambahkan logika sorting berdasarkan parameter 'sort' dan 'direction'
        if ($request->has('sort') && $request->has('direction')) {
            $direction = $request->input('direction');
            $query->orderBy($sort, $direction); // Menggunakan variabel $sort dari if sebelumnya
        } else {
            // Jika tidak ada parameter 'direction', secara default urutkan descending berdasarkan nomer dari sesi_bimbingan
            $query->orderBy('tbl_resume_bimbingan.sesi_bimbingan', 'desc');
        }

        // Paginate hasil query dengan 10 data per halaman
        $resumes = $query->paginate(10);

        // Mengembalikan view dengan data resumes
        return view('bimbingan.resume.index', compact('resumes'));
    }
    

    public function create()
    {
        $user = auth()->user();

        // Mendapatkan id_kota dari user yang sedang login
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        // Query untuk mencari user dengan role 2 yang ada dalam kota yang sama
        $dosen = DB::table('users')
                    ->join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                    ->where('users.role', 2)
                    ->where('tbl_kota_has_user.id_kota', $id_kota)
                    ->select('users.*')
                    ->get();

        return view('bimbingan.resume.create', compact('dosen'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_bimbingan' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'isi_resume_bimbingan' => 'required',
            'isi_revisi_bimbingan' => '',
            'tahapan_progres' => 'required',
            'sesi_bimbingan'=> 'required',
        ]);


        if ($request->input('jam_selesai') < $request->input('jam_mulai')) {
            session()->flash('error', 'Jam selesai harus lebih besar dari jam mulai.');
            return redirect()->back()->withInput();
        }

        $requestData = $request->all();
        if (empty($requestData['isi_revisi_bimbingan'])) {
            $requestData['isi_revisi_bimbingan'] = '-';
        }

        $resume = ResumeBimbinganModel::create($requestData);
        $id_resume_bimbingan = $resume->id_resume_bimbingan;

        // Mendapatkan id_kota dari user yang sedang login
        $user = auth()->user();

        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        // Menangani pilihan dosen dari form
        $selectedDosenId = $request->input('dosen');

        // Pastikan id_kota dan id_user_role_2 valid sebelum menyimpan
        if ($id_kota && $selectedDosenId) {
            KotaHasResumeBimbinganModel::create([
                'id_kota' => $id_kota,
                'id_user' => $selectedDosenId, // Menggunakan id dosen yang dipilih dari form
                'id_resume_bimbingan' => $id_resume_bimbingan,
            ]);

            session()->flash('success', 'Data resume berhasil ditambahkan');
            return redirect()->route('resume');
        } else {
            return redirect()->route('resume')->with('error', 'Gagal menyimpan data: id_kota atau dosen tidak valid.');
        }
    }

    public function detail($id)
    {
        $resumes = ResumeBimbinganModel::findOrFail($id);

        $tahapan_progres = '';
        if ($resumes->tahapan_progres == 1) {
            $tahapan_progres = "Seminar 2";
        } elseif ($resumes->tahapan_progres == 2) {
            $tahapan_progres = "Seminar 3";
        } elseif ($resumes->tahapan_progres == 3) {
            $tahapan_progres = "Sidang";
        } else {
            $tahapan_progres = "Unknown";
        }

        return view('bimbingan.resume.detail', compact('resumes', 'tahapan_progres'));
    }

    public function edit($id)
    {
        $resume = ResumeBimbinganModel::findOrFail($id);
        if (!$resume) {
            return redirect()->route('resume')->withErrors('Data tidak ditemukan.');
        }

        // Mengatur tahapan progres
        $tahapan_progres = '';
        if ($resume->tahapan_progres == 1) {
            $tahapan_progres = "Seminar 2";
        } elseif ($resume->tahapan_progres == 2) {
            $tahapan_progres = "Seminar 3";
        } elseif ($resume->tahapan_progres == 3) {
            $tahapan_progres = "Sidang";
        } else {
            $tahapan_progres = "Unknown";
        }

        // Mendapatkan id_kota dari user yang sedang login
        $user = auth()->user();
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        // Query untuk mencari user dengan role 2 yang ada dalam kota yang sama
        $dosen = DB::table('users')
                    ->join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                    ->where('users.role', 2)
                    ->where('tbl_kota_has_user.id_kota', $id_kota)
                    ->select('users.*')
                    ->get();

        // Mendapatkan dosen yang terkait dengan resume bimbingan
        $dosen_terkait = DB::table('tbl_kota_has_resume_bimbingan as krb')
                            ->join('users as u', 'krb.id_user', '=', 'u.id')
                            ->select('u.id as id_dosen_terkait', 'u.name as nama_dosen_terkait')
                            ->where('krb.id_resume_bimbingan', $id)
                            ->where('u.role', 2)
                            ->first();

        return view('bimbingan.resume.edit', compact('resume', 'tahapan_progres', 'dosen', 'dosen_terkait'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_bimbingan' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'isi_resume_bimbingan' => 'required',
            'isi_revisi_bimbingan' => '',
            'tahapan_progres' => 'required',
        ]);

        $resume = ResumeBimbinganModel::findOrFail($id);
        
        // Check if any field is changed
        $changes = $request->except(['_token']) != $resume->only(array_keys($request->except(['_token'])));

        if ($changes) {
            $resume->update($request->all());

            // Mendapatkan id_kota dari user yang sedang login
            $user = auth()->user();
            $id_kota = DB::table('tbl_kota_has_user')
                        ->where('id_user', $user->id)
                        ->value('id_kota');

            // Query untuk mencari id_user dengan role '2' dalam kota yang sama
            $dosen = DB::table('tbl_kota_has_user')
                        ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                        ->where('tbl_kota_has_user.id_kota', $id_kota)
                        ->where('users.role', 2)
                        ->select('users.id')
                        ->first();

            // Pastikan id_kota dan id_user_role_2 valid sebelum menyimpan
            if ($id_kota && $dosen) {
                // Cek apakah data sudah ada di pivot table
                $exists = KotaHasResumeBimbinganModel::where('id_resume_bimbingan', $id)
                            ->where('id_kota', $id_kota)
                            ->where('id_user', $dosen->id)
                            ->exists();

                if (!$exists) {
                    KotaHasResumeBimbinganModel::create([
                        'id_kota' => $id_kota,
                        'id_user' => $dosen->id, // Menggunakan id_user dengan role '2'
                        'id_resume_bimbingan' => $id,
                    ]);
                }
            }
            if ($request->input('jam_selesai') < $request->input('jam_mulai')) {
                session()->flash('error', 'Jam selesai harus lebih besar dari jam mulai');
                return redirect()->back()->withInput();
            }
            session()->flash('success', 'Data resume berhasil dirubah');
        }

        return redirect()->route('resume');
    }


    public function destroy($id)
    {
        $resume = ResumeBimbinganModel::findOrFail($id);
        Storage::delete('/resume'. $resume->id_resume_bimbingan);
        $resume->delete();

        session()->flash('success', 'Data berhasil dihapus');
        
        return redirect()->route('resume');
    }

    public function generatePdf($sesi_bimbingan)
    {
        $user = auth()->user();

        $dosen = DB::table('tbl_resume_bimbingan as rb')
                    ->join('tbl_kota_has_resume_bimbingan as krb', 'rb.id_resume_bimbingan', '=', 'krb.id_resume_bimbingan')
                    ->join('users as u', 'krb.id_user', '=', 'u.id')
                    ->select('rb.*', 'u.name as nama_dosen')
                    ->where('rb.id_resume_bimbingan', $sesi_bimbingan)
                    ->first();

        $resume = ResumeBimbinganModel::findOrFail($sesi_bimbingan);

        $pdf = PDF::loadView('bimbingan.generate', compact('resume', 'dosen'));

        return $pdf->download('resume bimbingan ke-' . $resume->sesi_bimbingan . '.pdf');
    }

}
