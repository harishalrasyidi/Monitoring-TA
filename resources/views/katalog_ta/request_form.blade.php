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
                            
                            <!-- Dropdown untuk Tujuan Request -->
                            <div class="form-group">
                                <label for="tujuan_request">Tujuan Request Katalog TA <span class="text-danger">*</span></label>
                                <select name="tujuan_request" id="tujuan_request" 
                                    class="form-control @error('tujuan_request') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tujuan Request --</option>
                                    <option value="referensi_penelitian" {{ old('tujuan_request') == 'referensi_penelitian' ? 'selected' : '' }}>
                                        Referensi untuk penelitian serupa
                                    </option>
                                    <option value="studi_metodologi" {{ old('tujuan_request') == 'studi_metodologi' ? 'selected' : '' }}>
                                        Mempelajari metodologi yang digunakan
                                    </option>
                                    <option value="studi_literatur" {{ old('tujuan_request') == 'studi_literatur' ? 'selected' : '' }}>
                                        Menambah referensi literatur
                                    </option>
                                    <option value="inspirasi_topik" {{ old('tujuan_request') == 'inspirasi_topik' ? 'selected' : '' }}>
                                        Mencari inspirasi topik TA
                                    </option>
                                    <option value="analisis_perbandingan" {{ old('tujuan_request') == 'analisis_perbandingan' ? 'selected' : '' }}>
                                        Analisis perbandingan penelitian
                                    </option>
                                    <option value="validasi_ide" {{ old('tujuan_request') == 'validasi_ide' ? 'selected' : '' }}>
                                        Validasi ide penelitian
                                    </option>
                                    <option value="pembelajaran_teknik" {{ old('tujuan_request') == 'pembelajaran_teknik' ? 'selected' : '' }}>
                                        Mempelajari teknik/tools yang digunakan
                                    </option>
                                    <option value="konsultasi_akademik" {{ old('tujuan_request') == 'konsultasi_akademik' ? 'selected' : '' }}>
                                        Konsultasi akademik dengan penulis
                                    </option>
                                    <option value="kolaborasi_penelitian" {{ old('tujuan_request') == 'kolaborasi_penelitian' ? 'selected' : '' }}>
                                        Mencari peluang kolaborasi penelitian
                                    </option>
                                    <option value="lainnya" {{ old('tujuan_request') == 'lainnya' ? 'selected' : '' }}>
                                        Lainnya (akan dijelaskan di pesan tambahan)
                                    </option>
                                </select>
                                @error('tujuan_request')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Pilih tujuan yang paling sesuai dengan kebutuhan Anda. Ini akan membantu anggota KoTA 
                                    memahami maksud permintaan Anda.
                                </small>
                            </div>
                            
                            <!-- Textarea untuk detail tambahan ketika pilih "Lainnya" -->
                            <div class="form-group" id="detail_lainnya" style="display: none;">
                                <label for="detail_tujuan">Detail Tujuan Request <span class="text-danger">*</span></label>
                                <textarea name="detail_tujuan" id="detail_tujuan" rows="3" 
                                    class="form-control @error('detail_tujuan') is-invalid @enderror" 
                                    placeholder="Jelaskan secara detail tujuan request Anda..."
                                    maxlength="500">{{ old('detail_tujuan') }}</textarea>
                                @error('detail_tujuan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Maksimal 500 karakter. <span id="char_count">0</span>/500
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <label for="pesan">Pesan Tambahan (Opsional)</label>
                                <textarea name="pesan" id="pesan" rows="3" 
                                    class="form-control @error('pesan') is-invalid @enderror" 
                                    placeholder="Pesan tambahan untuk anggota KoTA..."
                                    maxlength="1000">{{ old('pesan') }}</textarea>
                                @error('pesan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Anda dapat menambahkan pesan khusus atau pertanyaan untuk anggota KoTA.
                                    Maksimal 1000 karakter.
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
        // Show/Hide detail textarea when "Lainnya" is selected
        $('#tujuan_request').change(function() {
            if ($(this).val() === 'lainnya') {
                $('#detail_lainnya').slideDown();
                $('#detail_tujuan').attr('required', true);
            } else {
                $('#detail_lainnya').slideUp();
                $('#detail_tujuan').attr('required', false).val('');
            }
        });
        
        // Character counter for detail_tujuan
        $('#detail_tujuan').on('input', function() {
            var currentLength = $(this).val().length;
            $('#char_count').text(currentLength);
            
            if (currentLength > 450) {
                $('#char_count').parent().removeClass('text-muted').addClass('text-warning');
            } else if (currentLength > 500) {
                $('#char_count').parent().removeClass('text-warning').addClass('text-danger');
            } else {
                $('#char_count').parent().removeClass('text-warning text-danger').addClass('text-muted');
            }
        });
        
        // Confirm dialog before sending request
        $('#submitRequest').click(function(e) {
            e.preventDefault();
            
            // Validasi form terlebih dahulu
            var tujuanRequest = $('#tujuan_request').val();
            var detailTujuan = $('#detail_tujuan').val().trim();
            
            if (!tujuanRequest) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mohon pilih tujuan request terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#tujuan_request').focus();
                return;
            }
            
            if (tujuanRequest === 'lainnya' && !detailTujuan) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mohon jelaskan detail tujuan request Anda.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#detail_tujuan').focus();
                return;
            }
            
            // Get selected option text for display
            var selectedText = $('#tujuan_request option:selected').text();
            
            Swal.fire({
                title: 'Konfirmasi Request',
                html: `
                    <div class="text-left">
                        <p>Email request akan dikirim ke anggota KoTA:</p>
                        <p><strong>{{ $kota->nama_kota }}</strong></p>
                        <p><strong>Judul:</strong> {{ $kota->judul }}</p>
                        <p><strong>Tujuan:</strong> ${selectedText}</p>
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
        
        // Auto resize textarea
        $('textarea').each(function() {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
        }).on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Initialize on page load
        if ($('#tujuan_request').val() === 'lainnya') {
            $('#detail_lainnya').show();
            $('#detail_tujuan').attr('required', true);
        }
    });
</script>
@endpush
@endsection