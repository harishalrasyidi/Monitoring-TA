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
                        <a href="{{ route('katalog') }}" class="btn btn-secondary">
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
                                
                                {{-- Kondisi untuk download berdasarkan role --}}
                                @if($laporan['file_path'])
                                <hr>
                                    @if(auth()->user()->role != 3)
                                        <a href="{{ Storage::url($laporan['file_path']) }}" download class="btn btn-success btn-block">
                                            <i class="fas fa-download"></i> Unduh Lembar Pengesahan
                                        </a>
                                    @endif



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
                                            @if(auth()->user()->role == 3)
                                                {{-- Role 3: Hanya bisa view dengan warning --}}
                                                <div class="alert alert-warning mb-3">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    <strong>Akses Terbatas:</strong> Anda hanya dapat melihat dokumen, tidak dapat mengunduh.
                                                </div>
                                                <button id="show-pdf-btn" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-eye mr-2"></i>
                                                    Lihat Laporan (View Only)
                                                </button>
                                            @else
                                                {{-- Role lain: Akses penuh --}}
                                                <button id="show-pdf-btn" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-file-pdf mr-2"></i>
                                                    Lihat Laporan Lengkap
                                                </button>
                                            @endif
                                        </div>
                                            @if($laporan['poster_path'])
                                            <div class="text-center mt-4">
                                                <button id="show-poster-btn" class="btn btn-info btn-lg">
                                                    <i class="fas fa-image mr-2"></i>
                                                    Lihat Poster
                                                </button>
                                            </div>
                                            @endif
                                        @endif
                                    @else
                                        <div class="text-center p-5">
                                            <i class="fas fa-file-alt fa-5x text-secondary mb-3"></i>
                                            <h4 class="text-muted">Abstrak tidak tersedia</h4>
                                            @if($laporan['file_path'])
                                            <div class="mt-4">
                                                @if(auth()->user()->role == 3)
                                                    <div class="alert alert-warning mb-3">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        <strong>Akses Terbatas:</strong> Anda hanya dapat melihat dokumen, tidak dapat mengunduh.
                                                    </div>
                                                    <button id="show-pdf-btn" class="btn btn-primary btn-lg">
                                                        <i class="fas fa-eye mr-2"></i>
                                                        Lihat Laporan (View Only)
                                                    </button>
                                                @else
                                                    <button id="show-pdf-btn" class="btn btn-primary btn-lg">
                                                        <i class="fas fa-file-pdf mr-2"></i>
                                                        Lihat Laporan Lengkap
                                                    </button>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- PDF Section (hidden by default) -->
                                <div id="pdf-section" style="display: none; height: 750px; position: relative;">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <button id="show-abstract-btn" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left mr-2"></i>
                                                Kembali ke Abstrak
                                            </button>
                                        </div>
                                        
                                        @if(auth()->user()->role == 3)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning text-dark px-3 py-1 rounded mr-3" style="cursor: default; opacity: 0.9;">
                                                <small><i class="fas fa-lock mr-2"></i> Mode View Only</small>
                                            </div>
                                            <div id="page-info" class="bg-info text-white px-3 py-1 rounded" style="display: none;">
                                                <small><i class="fas fa-file-pdf mr-2"></i><span id="page-count">Loading...</span></small>
                                            </div>
                                        </div>
                                        @endif
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
                                        <div id="iframe-container" style="height: calc(100% - 50px);">
                                            {{-- Iframe akan dibuat dinamis melalui JavaScript --}}
                                        </div>
                                    @else
                                        <div class="text-center p-5">
                                            <i class="fas fa-file-pdf fa-5x text-secondary mb-3"></i>
                                            <h4 class="text-muted">Dokumen laporan tidak tersedia</h4>
                                        </div>
                                    @endif
                                </div>
                                <!-- Poster Section (hidden by default) -->
                                <div id="poster-section" style="display: none; height: 750px; position: relative;">
                                    <div class="mb-3">
                                        <button id="back-from-poster-btn" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            Kembali ke Abstrak
                                        </button>
                                    </div>
                                    <!-- Loading Indicator for Poster -->
                                    <div id="poster-loading" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                        <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                                        <p class="text-muted mt-2">Memuat poster...</p>
                                    </div>
                                    <!-- Iframe for Poster -->
                                    <div id="poster-iframe-container" style="height: calc(100% - 50px);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include PDF.js for page counting (only for role 3) --}}
    @if(auth()->user()->role == 3)
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    @endif

    <script>
        // Global variables
        let isPdfLoaded = false;
        let currentPdfUrl = '';

        document.addEventListener('DOMContentLoaded', function() {
            // Add to DOMContentLoaded event listener
            const showPosterBtn = document.getElementById('show-poster-btn');
            const backFromPosterBtn = document.getElementById('back-from-poster-btn');
            const posterSection = document.getElementById('poster-section');
            const posterLoading = document.getElementById('poster-loading');
            const posterIframeContainer = document.getElementById('poster-iframe-container');

            if (showPosterBtn) {
                showPosterBtn.addEventListener('click', function() {
                    showPosterSection();
                });
            }

            if (backFromPosterBtn) {
                backFromPosterBtn.addEventListener('click', function() {
                    showAbstractSection();
                });
            }

            function showPosterSection() {
                if (abstractSection) abstractSection.style.display = 'none';
                if (pdfSection) pdfSection.style.display = 'none';
                if (posterSection) posterSection.style.display = 'block';
                
                loadPoster();
            }

            function loadPoster() {
                if (!posterIframeContainer || !posterLoading) return;
                
                posterLoading.style.display = 'block';
                posterIframeContainer.innerHTML = '';
                
                const posterUrl = '{{ isset($laporan["poster_path"]) ? Storage::url($laporan["poster_path"]) : "" }}';
                
                const iframe = document.createElement('iframe');
                iframe.id = 'poster-iframe';
                iframe.src = posterUrl;
                iframe.width = '100%';
                iframe.height = '100%';
                iframe.frameBorder = '0';
                iframe.style.height = '100%';
                
                @if(auth()->user()->role == 3)
                iframe.setAttribute('oncontextmenu', 'return false');
                iframe.setAttribute('ondragstart', 'return false');
                iframe.setAttribute('onselectstart', 'return false');
                @endif
                
                iframe.onload = function() {
                    posterLoading.style.display = 'none';
                    posterIframeContainer.style.display = 'block';
                };
                
                posterIframeContainer.appendChild(iframe);
            }
            const showPdfBtn = document.getElementById('show-pdf-btn');
            const showAbstractBtn = document.getElementById('show-abstract-btn');
            const abstractSection = document.getElementById('abstract-section');
            const pdfSection = document.getElementById('pdf-section');
            const pdfLoading = document.getElementById('pdf-loading');
            const pdfError = document.getElementById('pdf-error');
            const iframeContainer = document.getElementById('iframe-container');

            // Debug information
            console.log('File path from server:', '{{ $laporan["file_path"] ?? "null" }}');
            console.log('Full URL:', '{{ isset($laporan["file_path"]) ? asset("/storage/submissions/" . $laporan["file_path"]) : "no-file" }}');
            
            // Disable right-click dan shortcut untuk role 3
            @if(auth()->user()->role == 3)
            // Disable right-click context menu
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            });

            // Disable common download shortcuts
            document.addEventListener('keydown', function(e) {
                // Disable Ctrl+S (Save)
                if (e.ctrlKey && e.keyCode === 83) {
                    e.preventDefault();
                    return false;
                }
                // Disable Ctrl+P (Print)
                if (e.ctrlKey && e.keyCode === 80) {
                    e.preventDefault();
                    return false;
                }
                // Disable F12 (Developer Tools)
                if (e.keyCode === 123) {
                    e.preventDefault();
                    return false;
                }
                // Disable Ctrl+Shift+I (Developer Tools)
                if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
                    e.preventDefault();
                    return false;
                }
            });

            // Show warning for restricted access
            if (showPdfBtn) {
                showPdfBtn.addEventListener('click', function() {
                    // Optional: Show additional warning
                    const confirmView = confirm('Anda akan melihat dokumen dalam mode terbatas (view only). Download dan print tidak tersedia. Lanjutkan?');
                    if (!confirmView) {
                        return;
                    }
                    
                    showPdfSection();
                });
            }
            @else
            // Normal behavior for other roles
            if (showPdfBtn) {
                showPdfBtn.addEventListener('click', function() {
                    console.log('Show PDF button clicked');
                    showPdfSection();
                });
            }
            @endif

            if (showAbstractBtn) {
                showAbstractBtn.addEventListener('click', function() {
                    console.log('Show abstract button clicked');
                    showAbstractSection();
                });
            }

            function showPdfSection() {
                if (abstractSection) abstractSection.style.display = 'none';
                if (pdfSection) pdfSection.style.display = 'block';
                
                // Load PDF only if not already loaded or if URL changed
                if (!isPdfLoaded) {
                    loadPdf();
                }
            }

            function showAbstractSection() {
                if (pdfSection) pdfSection.style.display = 'none';
                if (posterSection) posterSection.style.display = 'none'; // Tambahkan baris ini
                if (abstractSection) abstractSection.style.display = 'block';
            }


            // Function to load PDF with better error handling
            function loadPdf() {
                console.log('loadPdf called');

                // Check if PDF file exists first
                @if(!isset($laporan['file_path']) || empty($laporan['file_path']))
                    console.error('No PDF file available');
                    if (pdfError) {
                        pdfError.innerHTML = '<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><h4 class="text-muted">File PDF tidak tersedia</h4>';
                        pdfError.style.display = 'block';
                    }
                    return;
                @endif

                // Check if all required elements exist
                if (!iframeContainer || !pdfLoading || !pdfError) {
                    console.error('Required elements not found');
                    if (pdfError) {
                        pdfError.innerHTML = '<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><h4 class="text-muted">Error: Elemen tidak ditemukan</h4>';
                        pdfError.style.display = 'block';
                    }
                    return;
                }

                // Show loading, hide others
                pdfLoading.style.display = 'block';
                pdfError.style.display = 'none';
                iframeContainer.style.display = 'none';

                // Construct PDF URL with proper path
                const fileName = '{{ $laporan["file_path"] ?? "" }}';
                
                if (!fileName) {
                    console.error('No filename provided');
                    handlePdfError('File tidak ditemukan');
                    return;
                }

                @if(auth()->user()->role == 3)
                    // For role 3, disable toolbar
                    currentPdfUrl = `{{ asset('/storage/submissions/') }}/${fileName}#toolbar=0&navpanes=0&scrollbar=1`;
                @else
                    // For other roles, normal PDF
                    currentPdfUrl = `{{ asset('/storage/submissions/') }}/${fileName}`;
                @endif

                console.log('Constructed PDF URL:', currentPdfUrl);

                // Test if file exists first
                fetch(currentPdfUrl.split('#')[0], { method: 'HEAD' }) // Remove fragment for HEAD request
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response;
                    })
                    .then(() => {
                        console.log('File exists, loading PDF...');
                        // File exists, proceed to load
                        loadPdfInIframe(currentPdfUrl);
                    })
                    .catch(error => {
                        console.error('File check failed:', error);
                        handlePdfError(`File tidak dapat diakses: ${error.message}`);
                    });
            }

            function loadPdfInIframe(url) {
                // Clear iframe container
                iframeContainer.innerHTML = '';
                
                // Create new iframe
                const iframe = document.createElement('iframe');
                iframe.id = 'pdf-iframe';
                iframe.src = url;
                iframe.width = '100%';
                iframe.height = '100%';
                iframe.frameBorder = '0';
                iframe.style.height = '100%';
                
                @if(auth()->user()->role == 3)
                    iframe.setAttribute('oncontextmenu', 'return false;');
                    iframe.setAttribute('ondragstart', 'return false;');
                    iframe.setAttribute('onselectstart', 'return false;');
                @endif
                
                // Set up event listeners
                iframe.onload = function() {
                    console.log('PDF loaded successfully');
                    handlePdfLoad();
                    isPdfLoaded = true;
                    
                    @if(auth()->user()->role == 3)
                        // Load PDF.js to count pages for role 3
                        loadPdfPages(url.split('#')[0]); // Remove fragment for PDF.js
                    @endif
                };
                
                iframe.onerror = function() {
                    console.error('PDF loading failed');
                    handlePdfError('Gagal memuat PDF dalam iframe');
                };
                
                // Append iframe to container
                iframeContainer.appendChild(iframe);
                
                // Fallback timeout
                setTimeout(function() {
                    if (pdfLoading && pdfLoading.style.display !== 'none') {
                        console.log('PDF loading timeout');
                        handlePdfError('Timeout: PDF memerlukan waktu terlalu lama untuk dimuat');
                    }
                }, 15000); // 15 seconds timeout
            }
        });

        // Global functions for iframe events
        function handlePdfLoad() {
            console.log('handlePdfLoad called');
            const pdfLoading = document.getElementById('pdf-loading');
            const iframeContainer = document.getElementById('iframe-container');
            const pdfError = document.getElementById('pdf-error');

            if (pdfLoading) pdfLoading.style.display = 'none';
            if (pdfError) pdfError.style.display = 'none';
            if (iframeContainer) iframeContainer.style.display = 'block';

            console.log('PDF loaded successfully');
        }

        function handlePdfError(errorMessage = 'Gagal memuat dokumen. Silakan coba lagi.') {
            console.log('handlePdfError called with message:', errorMessage);
            const pdfLoading = document.getElementById('pdf-loading');
            const iframeContainer = document.getElementById('iframe-container');
            const pdfError = document.getElementById('pdf-error');

            if (pdfLoading) pdfLoading.style.display = 'none';
            if (iframeContainer) iframeContainer.style.display = 'none';
            if (pdfError) {
                pdfError.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h4 class="text-muted">${errorMessage}</h4>
                        <div class="mt-3">
                            <button class="btn btn-primary" onclick="location.reload()">
                                <i class="fas fa-refresh mr-2"></i>Refresh Halaman
                            </button>
                            <button class="btn btn-info ml-2" onclick="checkFileDebug()">
                                <i class="fas fa-bug mr-2"></i>Debug Info
                            </button>
                        </div>
                    </div>
                `;
                pdfError.style.display = 'block';
            }

            console.log('PDF loading failed:', errorMessage);
        }

        function requestFullAccess() {
            @if(auth()->user()->role == 3)
                alert('Anda tidak memiliki akses penuh untuk mengunduh dokumen. Silakan hubungi administrator untuk mendapatkan akses lebih lanjut.');
            @else
                // Implementasi request full access untuk role lain
                alert('Fitur request full access belum diimplementasikan.');
            @endif
        }
    </script>

    {{-- Additional CSS for role 3 restrictions --}}
    @if(auth()->user()->role == 3)
    <style>
        /* Disable text selection untuk role 3 */
        #pdf-section {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Hide print option jika ada */
        @media print {
            body { display: none !important; }
        }
        
        /* Custom styling for page info */
        #page-info {
            font-size: 12px;
            white-space: nowrap;
        }
    </style>
    @endif
@endsection