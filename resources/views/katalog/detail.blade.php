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
                                <!-- Tambahkan di sini -->
                                @if($laporan['abstrak'])
                                    <strong><i class="fas fa-align-left mr-1"></i> Abstrak Tugas Akhir</strong>
                                    <div class="bg-light p-3 rounded border mb-3" style="white-space: pre-wrap;">
                                        {{ $laporan['abstrak'] }}
                                    </div>
                                @endif

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
                                <a href="{{ Storage::url($laporan['poster_path']) }}" target="_blank" class="btn btn-info btn-block">
                                    <i class="fas fa-image"></i> Lihat Poster
                                </a>
                                @endif
                                
                                @if($laporan['file_path'])
                                <hr>
                                <a href="{{ Storage::url($laporan['file_path']) }}" download class="btn btn-success btn-block">
                                    <i class="fas fa-download"></i> Unduh Lembar Pengesahan
                                </a>
                                @endif

                                <hr>
                                <a href="#" class="btn btn-secondary btn-block" onclick="requestFullAccess()">
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
                                    <!-- Try multiple URL formats to ensure PDF loads -->
                                    <iframe 
                                        src="{{ Storage::url($laporan['file_path']) }}" 
                                        width="100%" 
                                        height="100%" 
                                        frameborder="0"
                                        id="pdfViewer">
                                    </iframe>
                                    
                                    <!-- Fallback if iframe doesn't work -->
                                    <div id="pdfFallback" style="display: none;" class="text-center p-5">
                                        <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                                        <h4>Dokumen PDF</h4>
                                        <p>Jika PDF tidak tampil, silakan:</p>
                                        <a href="{{ Storage::url($laporan['file_path']) }}" target="_blank" class="btn btn-primary mr-2">
                                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                                        </a>
                                        <a href="{{ Storage::url($laporan['file_path']) }}" download class="btn btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <br><br>
                                        <small class="text-muted">File: {{ $laporan['original_file_name'] }}</small>
                                    </div>
                                @else
                                    <div class="text-center p-5">
                                        <i class="fas fa-file-pdf fa-5x text-secondary mb-3"></i>
                                        <h4 class="text-muted">Dokumen laporan tidak tersedia</h4>
                                        <p class="text-muted">File belum diupload atau terjadi masalah pada path file.</p>
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

@push('scripts')
<script>
    // Check if PDF loaded successfully
    document.getElementById('pdfViewer').addEventListener('error', function() {
        document.getElementById('pdfViewer').style.display = 'none';
        document.getElementById('pdfFallback').style.display = 'block';
    });

    // Function for requesting full access
    function requestFullAccess() {
        // Implement your logic for requesting full access
        alert('Fitur request full access akan segera tersedia. Silakan hubungi admin untuk akses penuh.');
    }

    // Alternative PDF loading check
    $(document).ready(function() {
        // Check if iframe loads successfully after 3 seconds
        setTimeout(function() {
            try {
                var iframe = document.getElementById('pdfViewer');
                if (iframe && iframe.contentDocument && iframe.contentDocument.body.innerHTML === '') {
                    // If iframe is empty, show fallback
                    document.getElementById('pdfViewer').style.display = 'none';
                    document.getElementById('pdfFallback').style.display = 'block';
                }
            } catch (e) {
                // Cross-origin error means PDF might be loading, so leave it as is
                console.log('PDF viewer loading...');
            }
        }, 3000);
    });
</script>
@endpush
