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
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Program Studi</label>
                                    <select name="prodi" class="form-control" id="prodi-filter">
                                        <option value="">Semua Prodi</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->prodi }}" {{ request('prodi') == $prodi->prodi ? 'selected' : '' }}>
                                                @if($prodi->prodi == '1')
                                                    D3 TI A
                                                @elseif($prodi->prodi == '2')
                                                    D3 TI B
                                                @elseif($prodi->prodi == '3')
                                                    D4 TI A
                                                @elseif($prodi->prodi == '4')
                                                    D4 TI B
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-primary btn-block" type="submit">
                                        <i class="fas fa-search mr-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                @if($katalog->count() > 0)
                    @foreach($katalog as $item)
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0 text-truncate" title="{{ $item->judul }}">
                                        {{ \Illuminate\Support\Str::limit($item->judul, 40) }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    </div>
                                    <p class="card-text mb-1"><strong>Artefak:</strong> {{ $item->nama_artefak }}</p>
                                    <p class="card-text mb-1"><strong>Kota:</strong> {{ $item->nama_kota }}</p>
                                    <p class="card-text mb-1"><strong>Kategori:</strong> {{ $item->kategori }}</p>
                                    <p class="card-text mb-1"><strong>Program Studi:</strong> 
                                        @if($item->prodi == '1')
                                            D3 TI A
                                        @elseif($item->prodi == '2')
                                            D3 TI B
                                        @elseif($item->prodi == '3')
                                            D4 TI A
                                        @elseif($item->prodi == '4')
                                            D4 TI B
                                        @else
                                            {{ $item->prodi }}
                                        @endif
                                    </p>
                                    <p class="card-text text-muted">
                                        <small>Dikumpulkan pada: {{ \Carbon\Carbon::parse($item->waktu_pengumpulan)->format('d M Y') }}</small>
                                    </p>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit form when dropdown changes
        $('#kategori-filter, #prodi-filter, #tahun-filter').change(function() {
            $('#filter-form').submit();
        });
    });
</script>
@endsection