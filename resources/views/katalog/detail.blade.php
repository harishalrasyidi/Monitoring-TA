@extends('adminlte.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col">
                        <h1 class="m-0">Detail Laporan Tugas Akhir</h1>
                    </div>
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div><!-- /.row -->
                <hr/>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Detail Card -->
                    <div class="col-md-3">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Detail</h3>
                            </div>
                            <div class="card-body">
                                <strong><i class="fas fa-book mr-1"></i> Judul</strong>
                                <p class="text-muted">{{ $laporan['judul'] }}</p>
                                <hr>
                                <strong><i class="fas fa-user mr-1"></i> Penulis</strong>
                                <p class="text-muted">{{ $laporan['penulis'] }}</p>
                                <hr>
                                <strong><i class="fas fa-calendar mr-1"></i> Tahun</strong>
                                <p class="text-muted">{{ $laporan['tahun'] }}</p>
                                <hr>
                                <strong><i class="fas fa-building mr-1"></i> Program Studi</strong>
                                <p class="text-muted">{{ $laporan['program_studi'] }}</p>
                                <hr>
                                <strong><i class="fas fa-user-tie mr-1"></i> Pembimbing</strong>
                                <p class="text-muted">{{ $laporan['pembimbing'] }}</p>
                                <hr>
                                
                                @if($laporan['penguji'])
                                <strong><i class="fas fa-users mr-1"></i> Penguji</strong>
                                <p class="text-muted">{{ $laporan['penguji'] }}</p>
                                <hr>
                                @endif
                                
                                @if($laporan['kata_kunci'])
                                <strong><i class="fas fa-tags mr-1"></i> Kata Kunci</strong>
                                <p class="text-muted">{{ $laporan['kata_kunci'] }}</p>
                                @endif
                                
                                @if($laporan['poster_path'])
                                <hr>
                                <a href="{{ asset('/storage/submissions/' . $laporan['poster_path']) }}" target="_blank" class="btn btn-info btn-block">
                                    <i class="fas fa-image"></i> Lihat Poster
                                </a>
                                @endif
                                
                                <hr>
                                <a href="{{ asset('storage/' . $laporan['file_path']) }}" download class="btn btn-success btn-block">
                                    <i class="fas fa-download"></i> Unduh Lembar Pengesahan
                                </a>

                                <hr>
                                <a href="{{ asset('storage/' . $laporan['file_path']) }}" download class="btn btn-secondary btn-block">
                                    <i class="fas fa-download"></i> Request Full Akses TA
                                </a>
                            </div>
                        </div>
                        
                        <!-- Author Details Card -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Detail Penulis</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>NIM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($penulis as $mhs)
                                        <tr>
                                            <td>{{ $mhs->name }}</td>
                                            <td>{{ $mhs->nomor_induk }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Supervisor Details Card -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Detail Pembimbing</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>NIP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pembimbing as $dsn)
                                        <tr>
                                            <td>{{ $dsn->name }}</td>
                                            <td>{{ $dsn->nomor_induk }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- PDF Viewer -->
                    <div class="col-md-9">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Dokumen Tugas Akhir</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0" style="height: 750px;">
                                @if($laporan['file_path'])
                                    <!-- Using LaravelPDFViewer package -->
                                    <iframe src="{{ asset('/storage/submissions/' . $laporan['file_path']) }}" width="100%" height="100%" frameborder="0"></iframe>
                                @else
                                    <div class="text-center p-5">
                                        <i class="fas fa-file-pdf fa-5x text-secondary mb-3"></i>
                                        <h4 class="text-muted">Dokumen laporan tidak tersedia</h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection