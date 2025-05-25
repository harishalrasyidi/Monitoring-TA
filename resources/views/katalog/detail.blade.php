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
                                <strong><i class="fas fa-calendar mr-1"></i> Tahun</strong>
                                <p class="text-muted">{{ $laporan['tahun'] }}</p>
                                <hr>
                                <strong><i class="fas fa-building mr-1"></i> Program Studi</strong>
                                <p class="text-muted">
                                    @if($laporan['program_studi'] == 1)
                                        D3 Teknik Informatika
                                    @elseif($laporan['program_studi'] == 2)
                                        D4 Teknik Informatika
                                    @else
                                        {{ $laporan['program_studi'] }}
                                    @endif
                                </p>
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
                    
                    <!-- Abstract/PDF Viewer -->
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
                            <div class="card-body" style="min-height: 750px;">
                                <!-- Abstract Section (shown by default) -->
                                <div id="abstract-section">
                                    @if($laporan['abstrak'])
                                        <div class="text-center mb-4">
                                            <h4 class="mb-3 font-weight-bold">
                                                {{ $laporan['judul'] }}
                                            </h4>
                                        </div>
                                        <div class="abstract-content">
                                            <h5 class="mb-3">
                                                <i class="fas fa-file-alt text-primary mr-2"></i>
                                                Abstrak
                                            </h5>
                                            <div class="text-justify" style="line-height: 1.8; font-size: 16px;">
                                                {!! nl2br(e($laporan['abstrak'])) !!}
                                            </div>
                                        </div>
                                        
                                        @if($laporan['file_path'])
                                        <div class="text-center mt-4">
                                            <button id="show-pdf-btn" class="btn btn-primary btn-lg">
                                                <i class="fas fa-file-pdf mr-2"></i>
                                                Lihat Laporan Lengkap
                                            </button>
                                        </div>
                                        @endif
                                    @else
                                        <div class="text-center p-5">
                                            <i class="fas fa-file-alt fa-5x text-secondary mb-3"></i>
                                            <h4 class="text-muted">Abstrak tidak tersedia</h4>
                                            @if($laporan['file_path'])
                                            <div class="mt-4">
                                                <button id="show-pdf-btn" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-file-pdf mr-2"></i>
                                                    Lihat Laporan Lengkap
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- PDF Section (hidden by default) -->
                                <div id="pdf-section" style="display: none; height: 750px; position: relative;">
                                    <div class="mb-3">
                                        <button id="show-abstract-btn" class="btn btn-secondary mr-2">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            Kembali ke Abstrak
                                        </button>
                                        <button id="reload-pdf-btn" class="btn btn-info">
                                            <i class="fas fa-sync-alt mr-2"></i>
                                            Reload PDF
                                        </button>
                                    </div>
                                    <!-- Loading Indicator -->
                                    <div id="pdf-loading" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                        <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                                        <p class="text-muted mt-2">Memuat dokumen...</p>
                                    </div>
                                    <!-- Error Message -->
                                    <div id="pdf-error" style="display: none; text-align: center; padding: 20px;">
                                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                        <h4 class="text-muted">Gagal memuat dokumen. Silakan coba lagi.</h4>
                                    </div>
                                    <!-- Iframe for PDF -->
                                    @if($laporan['file_path'])
                                        <iframe id="pdf-iframe" src="{{ asset('/storage/submissions/' . $laporan['file_path']) }}" width="100%" height="100%" frameborder="0" style="height: calc(100% - 50px);"></iframe>
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showPdfBtn = document.getElementById('show-pdf-btn');
            const showAbstractBtn = document.getElementById('show-abstract-btn');
            const reloadPdfBtn = document.getElementById('reload-pdf-btn');
            const abstractSection = document.getElementById('abstract-section');
            const pdfSection = document.getElementById('pdf-section');
            const pdfIframe = document.getElementById('pdf-iframe');
            const pdfLoading = document.getElementById('pdf-loading');
            const pdfError = document.getElementById('pdf-error');

            // Function to load PDF
            function loadPdf() {
                console.log('loadPdf called');

                // Check if PDF file exists first
                @if(!isset($laporan['file_path']) || empty($laporan['file_path']))
                    console.error('No PDF file available');
                    return;
                @endif

                // Check if all required elements exist
                if (!pdfIframe || !pdfLoading || !pdfError) {
                    console.error('Required elements not found');
                    if (pdfError) pdfError.style.display = 'block';
                    return;
                }

                // Show loading, hide others
                pdfLoading.style.display = 'block';
                pdfError.style.display = 'none';
                pdfIframe.style.display = 'none';

                const pdfUrl = "{{ isset($laporan['file_path']) ? asset('/storage/submissions/' . $laporan['file_path']) : '' }}";
                console.log('Loading PDF from:', pdfUrl);

                if (!pdfUrl) {
                    console.error('PDF URL is empty');
                    handlePdfError();
                    return;
                }

                // Load PDF
                if (pdfIframe.src !== pdfUrl) {
                    pdfIframe.src = pdfUrl;
                } else {
                    // Force reload if same URL
                    pdfIframe.src = pdfUrl + '?t=' + Date.now();
                }

                // Add event listeners for iframe
                pdfIframe.addEventListener('load', function() {
                    handlePdfLoad();
                });
                pdfIframe.addEventListener('error', function() {
                    handlePdfError();
                });

                // Fallback timeout
                setTimeout(function() {
                    if (pdfLoading && pdfLoading.style.display !== 'none') {
                        console.log('PDF loading timeout');
                        handlePdfError();
                    }
                }, 10000); // 10 seconds timeout
            }

            if (showPdfBtn) {
                showPdfBtn.addEventListener('click', function() {
                    console.log('Show PDF button clicked');
                    if (abstractSection) abstractSection.style.display = 'none';
                    if (pdfSection) pdfSection.style.display = 'block';
                    loadPdf();
                });
            }

            if (showAbstractBtn) {
                showAbstractBtn.addEventListener('click', function() {
                    console.log('Show abstract button clicked');
                    if (pdfSection) pdfSection.style.display = 'none';
                    if (abstractSection) abstractSection.style.display = 'block';
                });
            }

            if (reloadPdfBtn) {
                reloadPdfBtn.addEventListener('click', function() {
                    console.log('Reload PDF button clicked');
                    loadPdf();
                });
            }

            // Load PDF automatically on page load
            @if($laporan['file_path'])
                loadPdf();
            @endif
        });

        // Global functions for iframe events
        function handlePdfLoad() {
            console.log('handlePdfLoad called');
            const pdfLoading = document.getElementById('pdf-loading');
            const pdfIframe = document.getElementById('pdf-iframe');
            const pdfError = document.getElementById('pdf-error');

            if (pdfLoading) pdfLoading.style.display = 'none';
            if (pdfError) pdfError.style.display = 'none';
            if (pdfIframe) pdfIframe.style.display = 'block';

            console.log('PDF loaded successfully');
        }

        function handlePdfError() {
            console.log('handlePdfError called');
            const pdfLoading = document.getElementById('pdf-loading');
            const pdfIframe = document.getElementById('pdf-iframe');
            const pdfError = document.getElementById('pdf-error');

            if (pdfLoading) pdfLoading.style.display = 'none';
            if (pdfIframe) pdfIframe.style.display = 'none';
            if (pdfError) pdfError.style.display = 'block';

            console.log('PDF loading failed');
        }
    </script>
@endsection