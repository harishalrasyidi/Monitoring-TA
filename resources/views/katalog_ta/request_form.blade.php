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
                           <strong>Info:</strong> Request Anda akan dikirim ke <strong>anggota KoTA</strong> 
                            untuk mendapatkan akses katalog TA. Email Anda (<strong>{{ Auth::user()->email }}</strong>) 
                            akan digunakan sebagai kontak balasan.
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
                                    <h6><strong>Informasi Anggota KoTA:</strong></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Informasi ini hanya untuk referensi. Jika Anda mengenal anggota KoTA, 
                                        Anda dapat menghubungi mereka secara langsung di luar sistem.
                                    </small>
                                    <div class="row mt-2">
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
                                    Pilih tujuan yang paling sesuai dengan kebutuhan Anda. Ini akan membantu 
                                    anggota KoTA dalam memproses permintaan Anda.
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

                             <!-- Status Akademik -->
                            <div class="form-group">
                                <label for="status_pemohon">Status Pemohon <span class="text-danger">*</span></label>
                                <select name="status_pemohon" id="status_pemohon" 
                                    class="form-control @error('status_pemohon') is-invalid @enderror" required>
                                    <option value="">-- Pilih Status Anda --</option>
                                    <option value="mahasiswa_aktif" {{ old('status_pemohon') == 'mahasiswa_aktif' ? 'selected' : '' }}>
                                        Mahasiswa Aktif (sedang menyusun TA)
                                    </option>
                                    <option value="mahasiswa_alumni" {{ old('status_pemohon') == 'mahasiswa_alumni' ? 'selected' : '' }}>
                                        Alumni/Mahasiswa yang telah lulus
                                    </option>
                                    <option value="dosen_internal" {{ old('status_pemohon') == 'dosen_internal' ? 'selected' : '' }}>
                                        Dosen Internal Jurusan
                                    </option>
                                    <option value="dosen_eksternal" {{ old('status_pemohon') == 'dosen_eksternal' ? 'selected' : '' }}>
                                        Dosen Eksternal/Institusi Lain
                                    </option>
                                    <option value="peneliti" {{ old('status_pemohon') == 'peneliti' ? 'selected' : '' }}>
                                        Peneliti/Praktisi
                                    </option>
                                </select>
                                @error('status_pemohon')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Status ini membantu anggota KoTA dalam menentukan prioritas dan jenis akses yang diberikan.
                                </small>
                            </div>

                             <!-- Request Priority -->
                            <div class="form-group">
                                <label for="prioritas">Tingkat Prioritas <span class="text-danger">*</span></label>
                                <select name="prioritas" id="prioritas" 
                                    class="form-control @error('prioritas') is-invalid @enderror" required>
                                    <option value="">-- Pilih Prioritas --</option>
                                    <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>
                                        🟢 Rendah (tidak mendesak, fleksible waktu)
                                    </option>
                                    <option value="sedang" {{ old('prioritas') == 'sedang' ? 'selected' : '' }}>
                                        🟡 Sedang (perlu dalam 1-2 minggu)
                                    </option>
                                    <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>
                                        🟠 Tinggi (dibutuhkan segera, dalam 3-7 hari)
                                    </option>
                                    <option value="urgent" {{ old('prioritas') == 'urgent' ? 'selected' : '' }}>
                                        🔴 Urgent (sangat mendesak, dalam 1-2 hari)
                                    </option>
                                </select>
                                @error('prioritas')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Prioritas ini membantu anggota KoTA dalam mengatur urutan pemrosesan request.
                                </small>
                            </div>

                            <!-- Deadline Request (Optional) -->
                            <div class="form-group">
                                <label for="deadline_request">Deadline Kebutuhan (Opsional)</label>
                                <input type="date" name="deadline_request" id="deadline_request" 
                                    class="form-control @error('deadline_request') is-invalid @enderror" 
                                    value="{{ old('deadline_request') }}" min="{{ date('Y-m-d') }}">
                                @error('deadline_request')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Jika ada deadline tertentu kapan Anda membutuhkan akses katalog ini.
                                </small>
                            </div>

                            <!-- Institusi/Afiliasi -->
                            <div class="form-group">
                                <label for="institusi">Institusi/Afiliasi <span class="text-danger">*</span></label>
                                <input type="text" name="institusi" id="institusi" 
                                    class="form-control @error('institusi') is-invalid @enderror" 
                                    placeholder="Contoh: Universitas XYZ, PT ABC, dll"
                                    value="{{ old('institusi') }}" maxlength="200" required>
                                @error('institusi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Sebutkan institusi atau afiliasi Anda saat ini.
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
                                    Anda dapat menambahkan informasi seperti konteks penelitian, pertanyaan spesifik, 
                                    atau detail lainnya yang membantu anggota KoTA memahami request Anda. Maksimal 1000 karakter.
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
            var statusPemohon = $('#status_pemohon').val();
            var prioritas = $('#prioritas').val();
            var institusi = $('#institusi').val().trim();
            
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

            if (!statusPemohon) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mohon pilih status pemohon terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#status_pemohon').focus();
                return;
            }

            if (!prioritas) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mohon pilih tingkat prioritas terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#prioritas').focus();
                return;
            }

            if (!institusi) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mohon isi institusi/afiliasi Anda.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#institusi').focus();
                return;
            }
            
            // Get selected option text for display
            var selectedText = $('#tujuan_request option:selected').text();
            var selectedStatus = $('#status_pemohon option:selected').text();
            var selectedPrioritas = $('#prioritas option:selected').text();
            
            Swal.fire({
                title: 'Konfirmasi Request',
                html: `
                    <div class="text-left">
                        <p>Email request akan dikirim ke anggota KoTA:</p>
                        <p><strong>{{ $kota->nama_kota }}</strong></p>
                        <p><strong>Judul:</strong> {{ $kota->judul }}</p>
                        <p><strong>Tujuan:</strong> ${selectedText}</p>
                        <p><strong>Status:</strong> ${selectedStatus}</p>
                        <p><strong>Prioritas:</strong> ${selectedPrioritas}</p>
                        <p><strong>Institusi:</strong> ${institusi}</p>
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