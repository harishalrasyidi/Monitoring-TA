@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1 class="m-0">Katalog Laporan Tugas Akhir</h1>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Search Box with better instructions -->
            <div class="row mb-4">
                <div class="col-md-8 offset-md-2">
                    <form method="GET" action="{{ route('laporan.index') }}" id="search-form">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-lg" 
                                   id="searchInput" 
                                   placeholder="Cari dengan minimal 3 kata kunci (contoh: sistem informasi akademik)" 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-lg" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle"></i> 
                            Tips: Gunakan minimal 3 kata kunci untuk hasil yang lebih akurat. 
                            Contoh: "manajemen data mahasiswa", "android e-commerce", dll.
                        </small>
                    </form>
                </div>
            </div>

            <!-- Search Results Info -->
            @if(request('search'))
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-search mr-2"></i>
                            Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                            @if($laporanList->total() > 0)
                                - Ditemukan {{ $laporanList->total() }} dokumen
                            @else
                                - Tidak ada dokumen yang ditemukan
                            @endif
                            <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary ml-3">
                                <i class="fas fa-times"></i> Hapus Filter
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cards Grid -->
            <div class="row" id="reportCards">
                @if($laporanList->count() > 0)
                    @foreach($laporanList as $item)
                        @php
                            // Get authors for this thesis
                            $penulis = \App\Models\User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                                ->where('tbl_kota_has_user.id_kota', $item->id_kota)
                                ->where('users.role', 3)
                                ->pluck('name')
                                ->implode(', ');
                        @endphp
                        
                        <div class="col-md-4 mb-4 report-card">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0 text-truncate" title="{{ $item->judul }}">
                                        {{ \Illuminate\Support\Str::limit($item->judul, 40) }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 text-center">
                                        <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                    </div>
                                    <p><strong>Penulis:</strong> {{ $penulis ?: 'Tidak ada data' }}</p>
                                    <p><strong>Tahun:</strong> {{ $item->periode }}</p>
                                    <p><strong>Program Studi:</strong> {{ $item->kelas }}</p>
                                    
                                    <!-- Highlight search terms in title if searching -->
                                    @if(request('search'))
                                        <p class="mt-3">
                                            <small class="text-muted">
                                                <strong>Judul lengkap:</strong><br>
                                                {!! str_ireplace(
                                                    explode(' ', request('search')), 
                                                    array_map(function($word) { return '<mark>' . $word . '</mark>'; }, explode(' ', request('search'))), 
                                                    $item->judul
                                                ) !!}
                                            </small>
                                        </p>
                                    @endif
                                </div>
                                <div class="card-footer bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            Dikumpulkan: {{ \Carbon\Carbon::parse($item->waktu_pengumpulan)->format('d M Y') }}
                                        </small>
                                        <a href="{{ route('laporan.show', $item->id_kota) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center py-5">
                            @if(request('search'))
                                <i class="fas fa-search fa-5x text-secondary mb-3"></i>
                                <h4 class="text-muted">Tidak ada hasil yang ditemukan</h4>
                                <p class="text-muted">
                                    Coba gunakan kata kunci yang berbeda atau pastikan menggunakan minimal 3 kata kunci yang relevan.
                                </p>
                                <a href="{{ route('laporan.index') }}" class="btn btn-primary">
                                    <i class="fas fa-list"></i> Lihat Semua Dokumen
                                </a>
                            @else
                                <i class="fas fa-folder-open fa-5x text-secondary mb-3"></i>
                                <h4 class="text-muted">Belum ada laporan tugas akhir</h4>
                                <p class="text-muted">Dokumen akan muncul di sini setelah diunggah ke sistem.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($laporanList->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $laporanList->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Validate search input
        $('#search-form').on('submit', function(e) {
            var searchTerm = $('#searchInput').val().trim();
            var words = searchTerm.split(' ').filter(word => word.length >= 3);
            
            if (searchTerm.length > 0 && words.length < 2 && searchTerm.length < 5) {
                e.preventDefault();
                alert('Gunakan minimal 3 kata kunci atau 1 kata dengan minimal 5 karakter untuk pencarian yang lebih akurat.');
                return false;
            }
        });
        
        // Auto-submit on enter (optional)
        $('#searchInput').on('keypress', function(e) {
            if (e.which === 13) {
                $('#search-form').submit();
            }
        });
    });
</script>
@endpush
@endsection