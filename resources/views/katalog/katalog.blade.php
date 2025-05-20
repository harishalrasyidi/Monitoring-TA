@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Katalog Tugas Akhir</h1>
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
                    <h3 class="card-title">Filter Tugas Akhir</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('katalog') }}" id="filter-form">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>KBK (Kelompok Bidang Keahlian)</label>
                                    <select name="kbk" class="form-control" id="kbk-filter">
                                        <option value="">Semua KBK</option>
                                        @foreach($kbks as $kbk)
                                            <option value="{{ $kbk->kbk }}" {{ request('kbk') == $kbk->kbk ? 'selected' : '' }}>
                                                {{ $kbk->kbk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Topik</label>
                                    <select name="topik" class="form-control" id="topik-filter">
                                        <option value="">Semua Topik</option>
                                        @foreach($topics as $topic)
                                            <option value="{{ $topic->topik }}" {{ request('topik') == $topic->topik ? 'selected' : '' }}>
                                                {{ $topic->topik }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select name="tahun" class="form-control" id="tahun-filter">
                                        <option value="">Semua Tahun</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year->tahun }}" {{ request('tahun') == $year->tahun ? 'selected' : '' }}>
                                                {{ $year->tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis TA</label>
                                    <select name="jenis_ta" class="form-control" id="jenis-ta-filter">
                                        <option value="">Semua Jenis</option>
                                        @foreach($jenis_tas as $jenis)
                                            <option value="{{ $jenis['id'] }}" {{ request('jenis_ta') == $jenis['id'] ? 'selected' : '' }}>
                                                {{ $jenis['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="metodologi-group" style="{{ request('jenis_ta') != 'development' ? 'display: none;' : '' }}">
                                    <label>Metodologi (SDLC)</label>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Program Studi</label>
                                    <select name="prodi" class="form-control" id="prodi-filter">
                                        <option value="">Semua Prodi</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi['id'] }}" {{ request('prodi') == $prodi['id'] ? 'selected' : '' }}>
                                                {{ $prodi['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Dosen Pembimbing</label>
                                    <select name="dosen_id" class="form-control" id="dosen-filter">
                                        <option value="">Semua Dosen</option>
                                        @foreach($dosens as $dosen)
                                            <option value="{{ $dosen->id }}" {{ request('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                                {{ $dosen->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                    <p class="card-text mb-1"><strong>Dokumen:</strong> {{ $item->nama_artefak }}</p>
                                    <p class="card-text mb-1"><strong>Kota:</strong> {{ $item->nama_kota }}</p>
                                    <p class="card-text mb-1"><strong>KBK:</strong> {{ $item->kbk ?? 'Tidak ada' }}</p>
                                    <p class="card-text mb-1"><strong>Topik:</strong> {{ $item->topik ?? 'Tidak ada' }}</p>
                                    <p class="card-text mb-1"><strong>Jenis TA:</strong> 
                                        @if($item->jenis_ta == 'analisis')
                                            Analisis
                                        @elseif($item->jenis_ta == 'development')
                                            Development ({{ $item->metodologi ?? 'Tidak ada' }})
                                        @else
                                            Tidak ada
                                        @endif
                                    </p>
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
                                    <p class="card-text mb-1"><strong>Dosen Pembimbing:</strong> {{ $item->dosen_name ?? 'Tidak ada' }}</p>
                                    <p class="card-text text-muted">
                                        <small>Dikumpulkan pada: {{ \Carbon\Carbon::parse($item->waktu_pengumpulan)->format('d M Y') }}</small>
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="{{ route('kota.showFile', ['nama_artefak' => $item->nama_artefak]) }}" 
                                       class="btn btn-sm btn-outline-primary btn-block" target="_blank">
                                        <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                    </a>
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
        // Toggle metodologi field based on jenis_ta selection
        $('#jenis-ta-filter').change(function() {
            if ($(this).val() === 'development') {
                $('#metodologi-group').show();
            } else {
                $('#metodologi-group').hide();
                $('#metodologi-filter').val('');
            }
        });
        
        // Auto-submit form when dropdown changes
        $('#kbk-filter, #topik-filter, #tahun-filter, #jenis-ta-filter, #metodologi-filter, #prodi-filter, #dosen-filter').change(function() {
            $('#filter-form').submit();
        });
    });
</script>
@endsection