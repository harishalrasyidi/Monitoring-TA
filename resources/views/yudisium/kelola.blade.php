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
                            <button type="button" class="btn btn-success btn-sm" id="btnGenerateYudisium">
                                <i class="fas fa-sync-alt mr-2"></i> Refresh
                            </button>
                            <a href="{{ route('yudisium.export') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel
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
                                <td>{{ $item->nama_kota }}</td>
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
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editYudisiumModal" data-id="{{ $item->id_yudisium }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
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

<!-- Edit Yudisium Modal -->
<div class="modal fade" id="editYudisiumModal" tabindex="-1" role="dialog" aria-labelledby="editYudisiumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editYudisiumModalLabel">Edit Status Yudisium</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('yudisium.update', '__ID__') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_yudisium" id="id_yudisium">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama KoTA</label>
                                <input type="text" class="form-control" id="nama_kota" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kelas</label>
                                <input type="text" class="form-control" id="kelas" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nilai TA</label>
                                <input type="number" step="0.01" class="form-control" name="nilai_akhir" id="nilai_akhir">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Yudisium</label>
                                <input type="date" class="form-control" name="tanggal_yudisium" id="tanggal_yudisium">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori Yudisium</label>
                                <select class="form-control select2" name="kategori_yudisium" id="kategori_yudisium">
                                    <option value="1">Yudisium 1</option>
                                    <option value="2">Yudisium 2</option>
                                    <option value="3">Yudisium 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select2" name="status" id="status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Yudisium Modal -->
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
                            <option value="{{ $p }}">{{ $p }}</option>
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
</div>
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
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Generate Yudisium Button
    $('#btnGenerateYudisium').click(function() {
        $('#generateYudisiumModal').modal('show');
    });

    // Edit Modal
    $('#editYudisiumModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        
        // Fetch data dari backend (simulasi, ganti dengan AJAX jika diperlukan)
        var data = {
            @foreach($yudisium as $item)
            '{{ $item->id_yudisium }}': {
                id_yudisium: '{{ $item->id_yudisium }}',
                nama_kota: '{{ $item->nama_kota }}',
                kelas: '{{ $item->kelas == "1" ? "D3-A" : ($item->kelas == "2" ? "D3-B" : ($item->kelas == "3" ? "D4-A" : ($item->kelas == "4" ? "D4-B" : $item->kelas))) }}',
                nilai_akhir: '{{ $item->nilai_akhir ?? "" }}',
                tanggal_yudisium: '{{ $item->tanggal_yudisium }}',
                kategori_yudisium: '{{ $item->kategori_yudisium }}',
                status: '{{ $item->status }}',
                keterangan: '{{ $item->keterangan ?? "" }}'
            },
            @endforeach
        };
        
        var modal = $(this);
        var yudisiumData = data[id];
        
        modal.find('#id_yudisium').val(yudisiumData.id_yudisium);
        modal.find('#nama_kota').val(yudisiumData.nama_kota);
        modal.find('#kelas').val(yudisiumData.kelas);
        modal.find('#nilai_akhir').val(yudisiumData.nilai_akhir);
        modal.find('#tanggal_yudisium').val(yudisiumData.tanggal_yudisium);
        modal.find('#kategori_yudisium').val(yudisiumData.kategori_yudisium).trigger('change');
        modal.find('#status').val(yudisiumData.status).trigger('change');
        modal.find('#keterangan').val(yudisiumData.keterangan);
        
        // Update action form dengan ID yang benar
        var form = modal.find('form');
        var actionUrl = "{{ route('yudisium.update', ':id') }}".replace(':id', id);
        form.attr('action', actionUrl);
    });

    // Form submit handling with SweetAlert
    $('form').on('submit', function(e) {
        if ($(this).attr('action').includes("{{ route('yudisium.update', '') }}")) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Simpan perubahan?',
                text: "Perubahan data yudisium akan disimpan",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                    Swal.fire(
                        'Tersimpan!',
                        'Data yudisium berhasil diperbarui.',
                        'success'
                    );
                }
            });
        } else if ($(this).attr('action') === "{{ route('yudisium.generate') }}") {
            e.preventDefault();
            
            Swal.fire({
                title: 'Generate yudisium?',
                text: "Sistem akan mengkategorikan mahasiswa berdasarkan kriteria yang telah ditentukan",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, generate!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                    Swal.fire(
                        'Berhasil!',
                        'Kategori yudisium berhasil di-generate.',
                        'success'
                    );
                }
            });
        }
    });
});
</script>
@endsection