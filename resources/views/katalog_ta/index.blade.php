@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1>Katalog Tugas Akhir</h1>
            <hr>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mb-3">
                    <form action="{{ route('katalog-ta.index') }}" method="GET" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari judul TA..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tambahkan tombol upload di sini --}}
            <div class="col-12 mb-3 text-right">
                <a href="{{ route('katalog-ta.create') }}" class="btn btn-success mb-3">
                    <i class="fas fa-plus"></i> Upload Katalog TA
                </a>
            </div>

            
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($katalog as $item)
                    <div class="col mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">{{ \Illuminate\Support\Str::limit($item->judul_ta, 40) }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                </div>
                                <p class="card-text">
                                    <strong>Periode:</strong> {{ $item->kota->periode ?? 'N/A' }}<br>
                                    <strong>Kelas:</strong> {{ $item->kota->kelas ?? 'N/A' }}
                                </p>
                                <p class="card-text text-muted small">
                                    {{ \Illuminate\Support\Str::limit($item->deskripsi, 100) }}
                                </p>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('katalog-ta.show', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('katalog-ta.request-form', $item->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-envelope"></i> Request
                                    </a>
                                </div>
                                <small class="text-muted d-block text-right mt-2">
                                    Dikumpulkan: {{ \Carbon\Carbon::parse($item->waktu_pengumpulan)->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle mr-2"></i> Tidak ada katalog TA yang tersedia.
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $katalog->links() }}
            </div>
        </div>
    </div>
</div>
@endsection