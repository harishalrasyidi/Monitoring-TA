@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="row mb-3">
            <div class="col-md-8">
                <h2>Request Katalog TA</h2>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('katalog-ta.show', $kota->id_kota) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Form Request: {{ $kota->judul }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Info:</strong> Email Anda (<strong>{{ Auth::user()->email }}</strong>) akan digunakan 
                            sebagai kontak untuk anggota KoTA. Anggota dapat menghubungi Anda secara langsung jika mereka bersedia
                            berbagi katalog TA.
                        </div>
                        
                        <!-- Info KoTA -->
                        <div class="card mb-3 bg-light">
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    <i class="fas fa-info-circle mr-2"></i>Informasi KoTA
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nama KoTA:</strong> {{ $kota->nama_kota }}</p>
                                        <p><strong>Judul TA:</strong> {{ $kota->judul }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Periode:</strong> <span class="badge badge-primary">{{ $kota->periode }}</span></p>
                                        <p><strong>Kelas:</strong> <span class="badge badge-success">{{ $kota->kelas }}</span></p>
                                    </div>
                                </div>
                                
                                <!-- Anggota KoTA -->
                                <div class="mt-3">
                                    <h6><strong>Anggota KoTA:</strong></h6>
                                    <div class="row">
                                        @if($mahasiswa->count() > 0)
                                            <div class="col-md-6">
                                                <p><strong>Mahasiswa:</strong></p>
                                                <ul class="list-unstyled">
                                                    @foreach($mahasiswa as $mhs)
                                                        <li><i class="fas fa-user text-info mr-1"></i>{{ $mhs->name }} ({{ $mhs->nomor_induk }})</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        @if($dosen->count() > 0)
                                            <div class="col-md-6">
                                                <p><strong>Pembimbing:</strong></p>
                                                <ul class="list-unstyled">
                                                    @foreach($dosen as $dsn)
                                                        <li><i class="fas fa-chalkboard-teacher text-warning mr-1"></i>{{ $dsn->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('katalog-ta.send-request', $kota->id_kota) }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="tujuan_request">Tujuan Request Katalog TA <span class="text-danger">*</span></label>
                                <textarea name="tujuan_request" id="tujuan_request" rows="5" 
                                    class="form-control @error('tujuan_request') is-invalid @enderror" 
                                    placeholder="Jelaskan tujuan Anda meminta katalog TA ini (contoh: untuk referensi penelitian, memahami metodologi yang digunakan, dll)..." 
                                    required>{{ old('tujuan_request') }}</textarea>
                                @error('tujuan_request')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Jelaskan dengan jelas mengapa Anda memerlukan katalog TA ini. Hal ini akan membantu anggota KoTA dalam
                                    mempertimbangkan permintaan Anda.
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <label for="pesan">Pesan Tambahan (Opsional)</label>
                                <textarea name="pesan" id="pesan" rows="3" 
                                    class="form-control @error('pesan') is-invalid @enderror" 
                                    placeholder="Pesan tambahan untuk anggota KoTA...">{{ old('pesan') }}</textarea>
                                @error('pesan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Anda dapat menambahkan pesan khusus atau pertanyaan untuk anggota KoTA.
                                </small>
                            </div>
                            
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitRequest">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Request
                                </button>
                                <a href="{{ route('katalog-ta.show', $kota->id_kota) }}" class="btn btn-secondary btn-lg ml-2">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Confirm dialog before sending request
        $('#submitRequest').click(function(e) {
            e.preventDefault();
            
            // Validasi form terlebih dahulu
            var tujuanRequest = $('#tujuan_request').val().trim();
            if (!tujuanRequest) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mohon isi tujuan request terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#tujuan_request').focus();
                return;
            }
            
            Swal.fire({
                title: 'Konfirmasi Request',
                html: `
                    <div class="text-left">
                        <p>Email request akan dikirim ke anggota KoTA:</p>
                        <p><strong>{{ $kota->nama_kota }}</strong></p>
                        <p><strong>Judul:</strong> {{ $kota->judul }}</p>
                        <hr>
                        <p class="text-muted">Pastikan informasi yang Anda berikan sudah benar.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-paper-plane"></i> Ya, Kirim Request',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Mengirim Request...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    $(this).closest('form').submit();
                }
            });
        });
        
        // Character counter for textarea
        $('#tujuan_request').on('input', function() {
            var maxLength = 1000;
            var currentLength = $(this).val().length;
            var remaining = maxLength - currentLength;
            
            if (!$(this).next('.char-counter').length) {
                $(this).after('<small class="char-counter text-muted float-right"></small>');
            }
            
            $(this).next('.char-counter').text(remaining + ' karakter tersisa');
            
            if (remaining < 50) {
                $(this).next('.char-counter').removeClass('text-muted').addClass('text-warning');
            } else if (remaining < 0) {
                $(this).next('.char-counter').removeClass('text-warning').addClass('text-danger');
            } else {
                $(this).next('.char-counter').removeClass('text-warning text-danger').addClass('text-muted');
            }
        });
        
        // Auto resize textarea
        $('textarea').each(function() {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
        }).on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
@endpush
@endsection