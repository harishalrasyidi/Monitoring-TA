@extends('adminlte.layouts.app')
@section('title', 'Kelola Yudisium')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Kelola Yudisium</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('yudisium.dashboard') }}">Dashboard Yudisium</a></li>
                        <li class="breadcrumb-item active">Kelola Yudisium</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filter Section -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-1"></i>
                        Filter Data
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('yudisium.kelola') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Periode</label>
                                    <select class="form-control select2" name="periode">
                                        <option value="">Semua Periode</option>
                                        @foreach($periodeList as $p)
                                        <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select class="form-control select2" name="kelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasList as $k)
                                        <option value="{{ $k }}" {{ $kelas == $k ? 'selected' : '' }}>
                                            @if($k == '1') D3-A
                                            @elseif($k == '2') D3-B
                                            @elseif($k == '3') D4-A
                                            @elseif($k == '4') D4-B
                                            @else {{ $k }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Kategori Yudisium</label>
                                    <select class="form-control select2" name="kategori">
                                        <option value="">Semua Kategori</option>
                                        <option value="1" {{ $kategori == '1' ? 'selected' : '' }}>Yudisium 1</option>
                                        <option value="2" {{ $kategori == '2' ? 'selected' : '' }}>Yudisium 2</option>
                                        <option value="3" {{ $kategori == '3' ? 'selected' : '' }}>Yudisium 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control select2" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label> </label>
                                    <div class="input-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('yudisium.kelola') }}" class="btn btn-default ml-2">
                                            Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Main Content -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        Daftar Yudisium
                    </h3>
                    <div class="card-tools">
                        <div class="btn-group mr-1">
                            <a href="{{ route('yudisium.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-2"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama KoTA</th>
                                <th width="10%">Kelas</th>
                                <th width="10%">Nilai TA</th>
                                <th width="10%">Periode</th>
                                <th width="10%">Kategori</th>
                                <th width="15%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($yudisium as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->judul }}</td>
                                <td>
                                    @if($item->kelas == '1') D3-A
                                    @elseif($item->kelas == '2') D3-B
                                    @elseif($item->kelas == '3') D4-A
                                    @elseif($item->kelas == '4') D4-B
                                    @else {{ $item->kelas }}
                                    @endif
                                </td>
                                <td>{{ $item->nilai_akhir ?? '-' }}</td>
                                <td>{{ $item->periode }}</td>
                                <td>
                                    <span class="badge {{ $item->kategori_yudisium == 1 ? 'badge-success' : ($item->kategori_yudisium == 2 ? 'badge-warning' : 'badge-danger') }}">
                                        Yudisium {{ $item->kategori_yudisium }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                    @elseif($item->status == 'approved')
                                    <span class="badge badge-info">Approved</span>
                                    @elseif($item->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('yudisium.detail', $item->id_yudisium) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('yudisium.edit', $item->id_yudisium) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('yudisium.destroy', $item->id_yudisium) }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data yudisium.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $yudisium->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

{{-- <!-- Generate Yudisium Modal -->
<div class="modal fade" id="generateYudisiumModal" tabindex="-1" role="dialog" aria-labelledby="generateYudisiumModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="generateYudisiumModalLabel">Generate Kategori Yudisium</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('yudisium.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Sistem akan mengkategorikan mahasiswa ke dalam Yudisium 1, 2, atau 3 berdasarkan kriteria berikut:</p>
                    <ul>
                        <li><strong>Yudisium 1:</strong> Nilai TA ≥ 85 dan tepat waktu</li>
                        <li><strong>Yudisium 2:</strong> Nilai TA ≥ 75 dan terlambat ≤ 14 hari</li>
                        <li><strong>Yudisium 3:</strong> Kondisi lainnya</li>
                    </ul>
                    <div class="form-group">
                        <label>Periode</label>
                        <select class="form-control select2" name="periode_generate" required>
                            <option value="">Pilih Periode</option>
                            @foreach($periodeList as $p)
                            <option value="{{ $p }}"    >{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-sync-alt mr-1"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<style>
    .table-responsive {
        min-height: 300px;
    }
    .card-footer {
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {
    console.log('JavaScript loaded successfully.');

    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // $('#btnGenerateYudisium').click(function() {
    //     console.log('Generate button clicked.');
    //     $('#generateYudisiumModal').modal('show');
    // });
});
</script>
@endsection