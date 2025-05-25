@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Katalog Dokumen Tugas Akhir</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Katalog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Search Box dengan instruksi -->
            <div class="row mb-4">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-search mr-2"></i>Pencarian Berdasarkan Abstrak TA
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('katalog') }}" id="search-form">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control form-control-lg" 
                                           id="searchInput" 
                                           placeholder="Masukkan minimal 3 kata kunci untuk pencarian yang akurat..." 
                                           value="{{ request('search') }}"
                                           autocomplete="off">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-lg" type="submit">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                                <!-- Keep existing filters -->
                                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                                <input type="hidden" name="prodi" value="{{ request('prodi') }}">
                                <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                <input type="hidden" name="metodologi" value="{{ request('metodologi') }}">
                                <input type="hidden" name="dosen" value="{{ request('dosen') }}">
                            </form>
                            
                            @if(request('search'))
                                <div class="mt-2">
                                    <small class="text-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Mencari: "<strong>{{ request('search') }}</strong>"
                                        @php
                                            $searchWords = explode(' ', trim(request('search')));
                                            $wordCount = count(array_filter($searchWords));
                                        @endphp
                                        ({{ $wordCount }} kata kunci)
                                        @if($wordCount >= 3)
                                            <span class="badge badge-success ml-1">Pencarian Optimal</span>
                                        @else
                                            <span class="badge badge-warning ml-1">Minimal 3 kata untuk hasil terbaik</span>
                                        @endif
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-2"></i>Filter Dokumen
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('katalog') }}" id="filter-form">
                        <!-- Keep search term when filtering -->
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        
                        <!-- Filter Row 1 -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-tag mr-1"></i>Kategori</label>
                                    <select name="kategori" class="form-control" id="kategori-filter">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->kategori }}" {{ request('kategori') == $category->kategori ? 'selected' : '' }}>
                                                @if($category->kategori == '1')
                                                    Riset
                                                @elseif($category->kategori == '2')
                                                    Develop
                                                @else
                                                    {{ $category->kategori }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-graduation-cap mr-1"></i>Program Studi</label>
                                    <select name="prodi" class="form-control" id="prodi-filter">
                                        <option value="">Semua Prodi</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->prodi }}" {{ request('prodi') == $prodi->prodi ? 'selected' : '' }}>
                                                @if($prodi->prodi == '1')
                                                    D3 Teknik Informatika
                                                @elseif($prodi->prodi == '2')
                                                    D4 Teknik Informatika
                                                @else
                                                    {{ $prodi->prodi }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar mr-1"></i>Tahun</label>
                                    <select name="tahun" class="form-control" id="tahun-filter">
                                        <option value="">Semua Tahun</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year->year }}" {{ request('tahun') == $year->year ? 'selected' : '' }}>
                                                {{ $year->year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-cogs mr-1"></i>Metodologi SDLC</label>
                                    <select name="metodologi" class="form-control" id="metodologi-filter">
                                        <option value="">Semua Metodologi</option>
                                        @foreach($metodologis as $metodologi)
                                            <option value="{{ $metodologi->metodologi }}" {{ request('metodologi') == $metodologi->metodologi ? 'selected' : '' }}>
                                                {{ $metodologi->metodologi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Row 2 -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fas fa-chalkboard-teacher mr-1"></i>Dosen Pembimbing</label>
                                    <select name="dosen" class="form-control" id="dosen-filter">
                                        <option value="">Semua Dosen</option>
                                        @foreach($dosens as $dosen)
                                            <option value="{{ $dosen->id }}" {{ request('dosen') == $dosen->id ? 'selected' : '' }}>
                                                {{ $dosen->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-filter mr-1"></i> Terapkan Filter
                                        </button>
                                        <a href="{{ route('katalog') }}" class="btn btn-secondary">
                                            <i class="fas fa-times mr-1"></i> Reset Semua
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters Display -->
                        @if(request()->hasAny(['kategori', 'prodi', 'tahun', 'metodologi', 'dosen', 'search']))
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-light py-2">
                                        <strong><i class="fas fa-filter mr-1"></i>Filter Aktif:</strong>
                                        @if(request('search'))
                                            <span class="badge badge-primary mr-1">
                                                <i class="fas fa-search mr-1"></i>Pencarian: {{ request('search') }}
                                            </span>
                                        @endif
                                        @if(request('kategori'))
                                            <span class="badge badge-info mr-1">
                                                <i class="fas fa-tag mr-1"></i>
                                                @if(request('kategori') == '1')
                                                    Riset
                                                @elseif(request('kategori') == '2')
                                                    Develop
                                                @else
                                                    {{ request('kategori') }}
                                                @endif
                                            </span>
                                        @endif
                                        @if(request('prodi'))
                                            <span class="badge badge-success mr-1">
                                                <i class="fas fa-graduation-cap mr-1"></i>
                                                @if(request('prodi') == '1')
                                                    D3 Teknik Informatika
                                                @elseif(request('prodi') == '2')
                                                    D4 Teknik Informatika
                                                @else
                                                    {{ request('prodi') }}
                                                @endif
                                            </span>
                                        @endif
                                        @if(request('tahun'))
                                            <span class="badge badge-warning mr-1">
                                                <i class="fas fa-calendar mr-1"></i>{{ request('tahun') }}
                                            </span>
                                        @endif
                                        @if(request('metodologi'))
                                            <span class="badge badge-secondary mr-1">
                                                <i class="fas fa-cogs mr-1"></i>{{ request('metodologi') }}
                                            </span>
                                        @endif
                                        @if(request('dosen'))
                                            @php
                                                $selectedDosen = $dosens->where('id', request('dosen'))->first();
                                            @endphp
                                            @if($selectedDosen)
                                                <span class="badge badge-dark mr-1">
                                                    <i class="fas fa-chalkboard-teacher mr-1"></i>{{ $selectedDosen->name }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Results Info -->
            @if($katalog->count() > 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Menampilkan {{ $katalog->count() }} dari {{ $katalog->total() }} dokumen tugas akhir
                    @if(request('search'))
                        yang sesuai dengan pencarian "<strong>{{ request('search') }}</strong>"
                    @endif
                </div>
            @endif

            <!-- Results Grid -->
            <div class="row">
                @if($katalog->count() > 0)
                    @foreach($katalog as $item)
                        @php
                            // Get authors for this thesis
                            $penulis = \App\Models\User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                                ->where('tbl_kota_has_user.id_kota', $item->id_kota)
                                ->where('users.role', 3)
                                ->pluck('name')
                                ->toArray();
                            
                            // Get supervisors for this thesis
                            $pembimbing = \App\Models\User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                                ->where('tbl_kota_has_user.id_kota', $item->id_kota)
                                ->where('users.role', 2)
                                ->pluck('name')
                                ->toArray();
                        @endphp
                        
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="card-title mb-0" title="{{ $item->judul }}"
                                        style="
                                            display: -webkit-box;
                                            -webkit-line-clamp: 4;
                                            -webkit-box-orient: vertical;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        ">
                                        {{ $item->judul }}
                                    </h6>
                                </div>
                                <div class="card-body">                            
                                    <p class="card-text mb-1">
                                        <strong><i class="fas fa-layer-group mr-1"></i>Kategori:</strong> 
                                        @if($item->kategori == '1')
                                            Riset
                                        @elseif($item->kategori == '2')
                                            Develop
                                        @else
                                            {{ $item->kategori }}
                                        @endif
                                    </p>
                                    
                                    <!-- Tambahkan metodologi jika ada -->
                                    @if(!empty($item->metodologi))
                                        <p class="card-text mb-1">
                                            <strong><i class="fas fa-cogs mr-1"></i>Metodologi:</strong> {{ $item->metodologi }}
                                        </p>
                                    @endif
                                    
                                    <p class="card-text mb-1"><i class="fas fa-graduation-cap mr-1"></i><strong>Program Studi:</strong> 
                                        @if($item->prodi == '1')
                                            D3 Teknik Informatika
                                        @elseif($item->prodi == '2')
                                            D4 Teknik Informatika
                                        @else
                                            {{ $item->prodi }}
                                        @endif
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong><i class="fas fa-calendar mr-1"></i>Periode:</strong> 
                                        {{ $item->periode }}
                                    </p>
                                      <p class="card-text mb-1">
                                        <strong><i class="fas fa-user mr-1"></i>Penulis:</strong> 
                                        @php
                                            $penulis = \App\Models\User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                                                ->where('tbl_kota_has_user.id_kota', $item->id_kota)
                                                ->where('users.role', 3)
                                                ->pluck('name')
                                                ->implode(', ');
                                        @endphp
                                        {{ $penulis ?: 'Tidak ada data' }}
                                    </p>
                                    
                                    
                                    <!-- Show search relevance if searching -->
                                    @if(request('search'))
                                        @php
                                            $searchWords = explode(' ', strtolower(request('search')));
                                            $titleWords = explode(' ', strtolower($item->judul));
                                            $matchCount = count(array_intersect($searchWords, $titleWords));
                                        @endphp
                                        @if($matchCount > 0)
                                            <div class="mb-2">
                                                <small class="badge badge-success">
                                                    {{ $matchCount }} kata cocok
                                                </small>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="card-footer bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($item->waktu_pengumpulan)->format('d M Y') }}
                                        </small>
                                        <a href="{{ route('laporan.show', $item->id_kota) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <h4>Tidak ada dokumen yang ditemukan</h4>
                            @if(request('search'))
                                <p>Tidak ada dokumen tugas akhir yang sesuai dengan pencarian "<strong>{{ request('search') }}</strong>"</p>
                                <p>Tips pencarian:</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-lightbulb text-warning mr-1"></i> Gunakan minimal 3 kata kunci</li>
                                    <li><i class="fas fa-lightbulb text-warning mr-1"></i> Coba kata kunci yang lebih umum</li>
                                    <li><i class="fas fa-lightbulb text-warning mr-1"></i> Periksa ejaan kata kunci</li>
                                </ul>
                                <a href="{{ route('katalog') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-list mr-1"></i>Lihat Semua Dokumen
                                </a>
                            @else
                                <p>Belum ada dokumen tugas akhir yang tersedia atau sesuai dengan filter yang dipilih.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($katalog->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $katalog->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal untuk memperbesar poster -->
<div class="modal fade" id="posterModal" tabindex="-1" role="dialog" aria-labelledby="posterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="posterModalLabel">Poster</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPosterImg" src="" alt="Poster" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form when dropdown changes
    $('#kategori-filter, #prodi-filter, #tahun-filter, #metodologi-filter, #dosen-filter').change(function() {
        $('#filter-form').submit();
    });
    
    // Search input enhancements
    $('#searchInput').on('input', function() {
        const searchTerm = $(this).val().trim();
        const wordCount = searchTerm.split(' ').filter(word => word.length > 0).length;
        
        // Show word count feedback
        let feedbackClass = 'text-muted';
        let feedbackText = wordCount + ' kata';
        
        if (wordCount >= 3) {
            feedbackClass = 'text-success';
            feedbackText += ' ✓ Optimal';
        } else if (wordCount > 0) {
            feedbackClass = 'text-warning';
            feedbackText += ' (minimal 3 untuk hasil terbaik)';
        }
        
        // Remove existing feedback
        $('#searchInput').next('.search-feedback').remove();
        
        // Add feedback if there's input
        if (searchTerm.length > 0) {
            $('#searchInput').after('<small class="search-feedback ' + feedbackClass + ' d-block mt-1">' + feedbackText + '</small>');
        }
    });
    
    // Search form validation
    $('#search-form').on('submit', function(e) {
        const searchTerm = $('#searchInput').val().trim();
        if (searchTerm.length > 0 && searchTerm.length < 3) {
            e.preventDefault();
            alert('Silakan masukkan minimal 3 karakter untuk pencarian yang lebih akurat.');
            return false;
        }
    });
    
    // Highlight search terms in results (if search is active)
    @if(request('search'))
        const searchTerms = "{{ request('search') }}".toLowerCase().split(' ');
        $('.card-title, .card-text').each(function() {
            let text = $(this).html();
            searchTerms.forEach(function(term) {
                if (term.length > 2) {
                    const regex = new RegExp('(' + term + ')', 'gi');
                    text = text.replace(regex, '<mark>$1</mark>');
                }
            });
            $(this).html(text);
        });
    @endif
});
</script>
@endpush