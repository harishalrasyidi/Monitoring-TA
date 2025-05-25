@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Katalog Dokumen</h1>
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Dokumen</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('katalog') }}" id="filter-form">
                        <div class="row">
                            <!-- Search Topik -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cari Topik/Judul</label>
                                    <div class="input-group">
                                        <input type="text" name="topik" class="form-control" 
                                               placeholder="Masukkan kata kunci..." 
                                               value="{{ request('topik') }}" id="topik-search">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Tahun -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tahun</label>
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

                            <!-- Filter Kategori (Riset/Development untuk D4) -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="kategori" class="form-control" id="kategori-filter">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->kategori }}" {{ request('kategori') == $category->kategori ? 'selected' : '' }}>
                                                {{ $category->kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Metodologi (Hanya untuk Development) -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Metodologi SDLC</label>
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

                            <!-- Filter Dosen Pembimbing -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Dosen Pembimbing</label>
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
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary mr-2" type="submit">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                                <a href="{{ route('katalog') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh mr-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
                                    <h5 class="card-title mb-0 text-truncate" title="{{ $item->judul }}">
                                        {{ \Illuminate\Support\Str::limit($item->judul, 40) }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Thumbnail Section - Display poster for Proposal Tugas Akhir -->
                                    <div class="text-center mb-3 poster-thumbnail" style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                                        @if($item->poster_file && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->poster_file))
                                            @php
                                                $fileExtension = strtolower(pathinfo($item->poster_file, PATHINFO_EXTENSION));
                                            @endphp
                                            @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                                <img src="{{ asset('storage/' . $item->poster_file) }}" 
                                                    alt="Poster {{ $item->judul }}" 
                                                    class="poster-image"
                                                    style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 8px; cursor: pointer;"
                                                    onclick="showPosterModal('{{ asset('storage/' . $item->poster_file) }}', '{{ $item->judul }}')">
                                            @else
                                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                                    <i class="fas fa-file-image fa-3x mb-2"></i>
                                                    <span>Poster ({{ strtoupper($fileExtension) }})</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <span>Tidak ada poster</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <p class="card-text mb-1"><strong>Artefak:</strong> {{ $item->nama_artefak }}</p>
                                    <p class="card-text mb-1"><strong>KoTA:</strong> {{ $item->nama_kota }}</p>
                                    
                                    @if($item->topik)
                                        <p class="card-text mb-1"><strong>Topik:</strong> {{ \Illuminate\Support\Str::limit($item->topik, 30) }}</p>
                                    @endif
                                    
                                    @if($item->kategori)
                                        <p class="card-text mb-1"><strong>Kategori:</strong> {{ $item->kategori }}</p>
                                    @endif
                                    
                                    @if($item->metodologi && ($item->kategori == 'Development'))
                                        <p class="card-text mb-1"><strong>Metodologi:</strong> {{ $item->metodologi }}</p>
                                    @endif
                                    
                                    <p class="card-text mb-1"><strong>Program Studi:</strong> 
                                        @if($item->prodi == '1')
                                            D3 Teknik Informatika
                                        @elseif($item->prodi == '2')
                                            D4 Teknik Informatika
                                        @else
                                            {{ $item->prodi }}
                                        @endif
                                    </p>
                                    
                                    @if(count($penulis) > 0)
                                        <p class="card-text mb-1"><strong>Penulis:</strong> {{ implode(', ', $penulis) }}</p>
                                    @endif
                                    
                                    @if(count($pembimbing) > 0)
                                        <p class="card-text mb-1"><strong>Pembimbing:</strong> {{ implode(', ', $pembimbing) }}</p>
                                    @endif
                                </div>
                                <div class="card-footer bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Dikumpulkan: {{ \Carbon\Carbon::parse($item->waktu_pengumpulan)->format('d M Y') }}</small>
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
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle mr-2"></i> Tidak ada dokumen yang sesuai dengan filter yang dipilih
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $katalog->appends(request()->query())->links() }}
            </div>
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

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit form when dropdown changes (except for text input)
        $('#tahun-filter, #kategori-filter, #metodologi-filter, #dosen-filter').change(function() {
            $('#filter-form').submit();
        });

        // Show/hide metodologi field based on kategori selection
        $('#kategori-filter').change(function() {
            if ($(this).val() === 'Development') {
                $('#metodologi-wrapper').show();
            } else {
                $('#metodologi-wrapper').hide();
                $('#metodologi-filter').val(''); // Reset metodologi selection
            }
        });

        // Submit form when Enter is pressed in search box
        $('#topik-search').keypress(function(e) {
            if (e.which == 13) { // Enter key
                $('#filter-form').submit();
            }
        });
    });

    // Function to show poster modal
    function showPosterModal(imageSrc, title) {
        $('#modalPosterImg').attr('src', imageSrc);
        $('#posterModalLabel').text('Poster - ' + title);
        $('#posterModal').modal('show');
    }
</script>

<style>
    /* Custom styles untuk thumbnail poster */
    .poster-image {
        transition: transform 0.2s ease-in-out;
    }
    
    .poster-image:hover {
        transform: scale(1.05);
    }
    
    .poster-thumbnail {
        position: relative;
        transition: box-shadow 0.2s ease-in-out;
    }
    
    .poster-thumbnail:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    /* Loading effect untuk gambar */
    .poster-image {
        background-color: #f8f9fa;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .poster-thumbnail {
            height: 150px !important;
        }
    }
    
    @media (max-width: 576px) {
        .poster-thumbnail {
            height: 120px !important;
        }
    }
</style>
@endsection