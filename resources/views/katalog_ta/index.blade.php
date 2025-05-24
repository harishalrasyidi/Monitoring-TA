@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Katalog Tugas Akhir</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Katalog TA</li>
                    </ol>
                </div>
            </div>
            <hr>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            <!-- Filter dan Search Bar -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter & Pencarian</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('katalog-ta.index') }}" method="GET" class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Cari judul, nama kota..." 
                                               value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select name="periode" class="form-control">
                                        <option value="">Semua Periode</option>
                                        @foreach($periodeList as $periode)
                                            <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>
                                                {{ $periode }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select name="kelas" class="form-control">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasList as $kelas)
                                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                                {{ $kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Singkat -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $katalog->total() }}</h3>
                            <p>Total KoTA</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $katalog->count() }}</h3>
                            <p>Ditampilkan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $periodeList->count() }}</h3>
                            <p>Periode</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $kelasList->count() }}</h3>
                            <p>Kelas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Katalog Cards -->
            <div class="row">
                @forelse($katalog as $kota)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-graduation-cap mr-2"></i>
                                    {{ \Illuminate\Support\Str::limit($kota->judul, 45) }}
                                </h5>
                            </div>
                            
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="fas fa-project-diagram fa-3x text-secondary"></i>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <p class="card-text">
                                            <strong><i class="fas fa-calendar text-primary"></i> Periode:</strong><br>
                                            <span class="badge badge-primary">{{ $kota->periode }}</span>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <p class="card-text">
                                            <strong><i class="fas fa-users text-success"></i> Kelas:</strong><br>
                                            <span class="badge badge-success">{{ $kota->kelas }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="mb-3">
                                    <strong><i class="fas fa-map-marker-alt text-warning"></i> Nama KoTA:</strong><br>
                                    <span class="text-muted">{{ $kota->nama_kota }}</span>
                                </div>
                                
                                <!-- Anggota Team -->
                                <div class="mb-3">
                                    <strong><i class="fas fa-user-friends text-info"></i> Anggota:</strong><br>
                                    @if($kota->users->count() > 0)
                                        <small class="text-muted">
                                            {{ $kota->users->where('role', 3)->count() }} Mahasiswa, 
                                            {{ $kota->users->where('role', 2)->count() }} Dosen
                                        </small>
                                        <br>
                                        @foreach($kota->users->where('role', 3)->take(2) as $mahasiswa)
                                            <span class="badge badge-light">{{ $mahasiswa->name }}</span>
                                        @endforeach
                                        @if($kota->users->where('role', 3)->count() > 2)
                                            <span class="badge badge-secondary">+{{ $kota->users->where('role', 3)->count() - 2 }} lainnya</span>
                                        @endif
                                    @else
                                        <small class="text-muted">Belum ada anggota</small>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('katalog-ta.show', $kota->id_kota) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('katalog-ta.request-form', $kota->id_kota) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-envelope"></i> Request
                                        </a>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    @if($kota->isCompleted())
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Progress
                                        </span>
                                    @endif
                                </div>
                                
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-clock"></i> 
                                    Update terakhir: {{ $kota->updated_at ? $kota->updated_at->format('d M Y') : 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted">Tidak ada katalog TA yang ditemukan</h4>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'periode', 'kelas']))
                                        Coba ubah filter pencarian Anda.
                                    @else
                                        Belum ada KoTA yang terdaftar dalam sistem.
                                    @endif
                                </p>
                                @if(request()->hasAny(['search', 'periode', 'kelas']))
                                    <a href="{{ route('katalog-ta.index') }}" class="btn btn-primary">
                                        <i class="fas fa-redo"></i> Reset Filter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($katalog->hasPages())
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $katalog->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.small-box .icon {
    top: -10px;
    right: 10px;
}
</style>
@endpush
@endsection