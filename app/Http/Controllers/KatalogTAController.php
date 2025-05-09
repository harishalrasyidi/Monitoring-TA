<?php
namespace App\Http\Controllers;

use App\Models\KatalogTA;
use App\Models\KotaModel;
use App\Models\User;
use App\Models\AnggotaTA;
use App\Models\KotaHasUserModel; // Added this import
use App\Mail\RequestKatalogEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KatalogTAController extends Controller
{
    // Tambahkan middleware auth
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Tampilkan daftar katalog TA
    public function index(Request $request)
    {
        $query = KatalogTA::with('kota');
        
        // Filter pencarian jika ada
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('judul_ta', 'LIKE', "%{$search}%")
                  ->orWhereHas('kota', function($q) use ($search) {
                      $q->where('nama_kota', 'LIKE', "%{$search}%");
                  });
        }
        
        $katalog = $query->orderBy('waktu_pengumpulan', 'desc')->paginate(9);
        
        return view('katalog_ta.index', compact('katalog'));
    }
    
    // Tampilkan detail katalog TA
    public function show($id)
    {
        $katalog = KatalogTA::with(['kota', 'anggota.user'])->findOrFail($id);
        return view('katalog_ta.show', compact('katalog'));
    }
    
    // Tampilkan form upload katalog TA
    public function create()
    {
        $kotaList = KotaModel::all();
        // Get all students regardless of kota assignment initially
        $mahasiswaList = User::where('role', 'mahasiswa')->orderBy('name', 'asc')->get();
        
        return view('katalog_ta.create', compact('kotaList', 'mahasiswaList'));
    }
    
    // Get mahasiswa by kota (for AJAX)
    public function getMahasiswaByKota($id_kota)
    {
        // Get users associated with the selected kota from tbl_kota_has_user
        $mahasiswaList = User::whereHas('kotaRelasi', function($query) use ($id_kota) {
                $query->where('id_kota', $id_kota);
            })
            ->where('role', 'mahasiswa')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'nim']);
        
        return response()->json($mahasiswaList);
    }
    
    // Proses upload katalog TA
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'judul_ta' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_kota' => 'required|exists:tbl_kota,id_kota',
            'anggota_kelompok' => 'required|array|min:1|max:3',
            'anggota_kelompok.*' => 'exists:users,id',
            'file_ta' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);
        
        // Upload file
        $file = $request->file('file_ta');
        $fileName = time() . '_' . Str::slug($request->judul_ta) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('katalog_ta', $fileName, 'public');
        
        // Simpan katalog TA
        $katalogTA = KatalogTA::create([
            'id_kota' => $validated['id_kota'],
            'judul_ta' => $validated['judul_ta'],
            'deskripsi' => $validated['deskripsi'],
            'file_ta' => $filePath,
            'waktu_pengumpulan' => now(),
        ]);
        
        // Simpan relasi anggota TA
        foreach ($validated['anggota_kelompok'] as $userId) {
            // Check if user is already associated with this kota
            $existingRelation = KotaHasUserModel::where('id_user', $userId)
                ->where('id_kota', $validated['id_kota'])
                ->first();
                
            if (!$existingRelation) {
                // Create relation in tbl_kota_has_user if it doesn't exist
                KotaHasUserModel::create([
                    'id_user' => $userId,
                    'id_kota' => $validated['id_kota'],
                    'id_katalog' => $katalogTA->id
                ]);
            } else {
                // Update existing relation with katalog ID
                $existingRelation->update([
                    'id_katalog' => $katalogTA->id
                ]);
            }
        }
        
        return redirect()->route('katalog-ta.index')
            ->with('success', 'Katalog TA berhasil diupload.');
    }
    
    // Tambahkan fungsi edit dan update katalog TA
    public function edit($id)
    {
        $katalog = KatalogTA::with('anggota.user')->findOrFail($id);
        
        // Periksa apakah user adalah pemilik TA
        $isOwner = $katalog->anggota->contains('id_user', Auth::id());
        
        if (!$isOwner && !Auth::user()->hasRole('admin')) {
            return redirect()->route('katalog-ta.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit katalog TA ini.');
        }
        
        $kotaList = KotaModel::all();
        $mahasiswaList = User::where('role', 'mahasiswa')->orderBy('name', 'asc')->get();
        $selectedAnggota = $katalog->anggota->pluck('id_user')->toArray();
        
        return view('katalog_ta.edit', compact('katalog', 'kotaList', 'mahasiswaList', 'selectedAnggota'));
    }
    
    public function update(Request $request, $id)
    {
        $katalog = KatalogTA::findOrFail($id);
        
        // Periksa apakah user adalah pemilik TA
        $isOwner = $katalog->anggota->contains('id_user', Auth::id());
        
        if (!$isOwner && !Auth::user()->hasRole('admin')) {
            return redirect()->route('katalog-ta.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit katalog TA ini.');
        }
        
        // Validasi input
        $validated = $request->validate([
            'judul_ta' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_kota' => 'required|exists:tbl_kota,id_kota',
            'anggota_kelompok' => 'required|array|min:1|max:3',
            'anggota_kelompok.*' => 'exists:users,id',
            'file_ta' => 'nullable|file|mimes:pdf|max:10240', // max 10MB
        ]);
        
        // Update file jika ada
        if ($request->hasFile('file_ta')) {
            // Hapus file lama
            if ($katalog->file_ta) {
                Storage::disk('public')->delete($katalog->file_ta);
            }
            
            // Upload file baru
            $file = $request->file('file_ta');
            $fileName = time() . '_' . Str::slug($request->judul_ta) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('katalog_ta', $fileName, 'public');
            
            $katalog->file_ta = $filePath;
        }
        
        // Update katalog TA
        $katalog->judul_ta = $validated['judul_ta'];
        $katalog->deskripsi = $validated['deskripsi'];
        $katalog->id_kota = $validated['id_kota'];
        $katalog->save();
        
        // Update relasi anggota TA
        // Remove existing relationships
        KotaHasUserModel::where('id_katalog', $katalog->id)->update(['id_katalog' => null]);
        
        foreach ($validated['anggota_kelompok'] as $userId) {
            // Check if user is already associated with this kota
            $existingRelation = KotaHasUserModel::where('id_user', $userId)
                ->where('id_kota', $validated['id_kota'])
                ->first();
                
            if (!$existingRelation) {
                // Create relation in tbl_kota_has_user if it doesn't exist
                KotaHasUserModel::create([
                    'id_user' => $userId,
                    'id_kota' => $validated['id_kota'],
                    'id_katalog' => $katalog->id
                ]);
            } else {
                // Update existing relation with katalog ID
                $existingRelation->update([
                    'id_katalog' => $katalog->id
                ]);
            }
        }
        
        return redirect()->route('katalog-ta.show', $katalog->id)
            ->with('success', 'Katalog TA berhasil diperbarui.');
    }
    
    // Tampilkan form request katalog TA
    public function showRequestForm($id)
    {
        $katalog = KatalogTA::with(['kota', 'anggota.user'])->findOrFail($id);
        return view('katalog_ta.request_form', compact('katalog'));
    }
    
    // Proses request katalog TA
    public function sendRequest(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'tujuan_request' => 'required|string|max:1000',
        ]);
        
        // Ambil data katalog TA
        $katalog = KatalogTA::with(['kota', 'anggota.user'])->findOrFail($id);
        
        // Data pengirim (user yang sedang login)
        $requester = Auth::user();
        
        // Siapkan data email
        $data_email = [
            'subject' => 'Request Katalog TA: ' . $katalog->judul_ta,
            'sender_name' => $requester->name,
            'sender_email' => $requester->email,
            'tujuan_request' => $validated['tujuan_request'],
            'judul_ta' => $katalog->judul_ta,
            'katalog_id' => $katalog->id,
            'kota_nama' => $katalog->kota->nama_kota ?? 'N/A',
            'periode' => $katalog->kota->periode ?? 'N/A',
            'kelas' => $katalog->kota->kelas ?? 'N/A'
        ];
        
        // Kirim email ke setiap penulis TA
        $emailSent = false;
        
        // Ambil semua anggota TA dari tbl_kota_has_user
        $penulisList = User::whereHas('kotaUsers', function($query) use ($katalog) {
            $query->where('id_kota', $katalog->id_kota);
        })->get();
        
        
        
        foreach ($penulisList as $penulis) {
            if ($penulis && $penulis->email) {
                Mail::to($penulis->email)->send(new RequestKatalogEmail($data_email));
                $emailSent = true;
            }
        }
        
        if (!$emailSent) {
            return redirect()->back()->with('error', 'Tidak ada penulis TA yang dapat dihubungi saat ini.');
        }
        
        // Redirect dengan pesan sukses
        return redirect()->route('katalog-ta.show', $id)
            ->with('success', 'Request katalog berhasil dikirim ke penulis TA. Mereka akan menghubungi Anda melalui email jika bersedia berbagi katalog.');
    }
    
    // Download Katalog TA (hanya untuk admin dan pemilik TA)
    public function download($id)
    {
        $katalog = KatalogTA::with('anggota')->findOrFail($id);
        
        // Cek apakah user adalah admin atau pemilik TA
        $isOwner = $katalog->anggota->contains('id_user', Auth::id());
        
        if (!$isOwner && !Auth::user()->hasRole('admin')) {
            return redirect()->route('katalog-ta.show', $id)
                ->with('error', 'Anda tidak memiliki akses untuk mengunduh file ini.');
        }
        
        // Periksa apakah file ada
        if (!Storage::disk('public')->exists($katalog->file_ta)) {
            return redirect()->route('katalog-ta.show', $id)
                ->with('error', 'File TA tidak ditemukan.');
        }
        
        // Download file
        return Storage::disk('public')->download($katalog->file_ta, Str::slug($katalog->judul_ta) . '.pdf');
    }
}