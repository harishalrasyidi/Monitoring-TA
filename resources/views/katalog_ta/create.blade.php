@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="row mb-3">
            <div class="col-md-8">
                <h2>Tambah Katalog Tugas Akhir</h2>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('katalog-ta.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Form Tambah TA</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('katalog-ta.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="judul_ta">Judul Tugas Akhir <span class="text-danger">*</span></label>
                                <input type="text" name="judul_ta" id="judul_ta" 
                                    class="form-control @error('judul_ta') is-invalid @enderror" 
                                    value="{{ old('judul_ta') }}" required>
                                @error('judul_ta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" 
                                    class="form-control @error('deskripsi') is-invalid @enderror" 
                                    required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_kota">Kota <span class="text-danger">*</span></label>
                                <select name="id_kota" id="id_kota" 
                                    class="form-control @error('id_kota') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach($kotaList as $kota)
                                        <option value="{{ $kota->id_kota }}" 
                                            {{ old('id_kota') == $kota->id_kota ? 'selected' : '' }}>
                                            {{ $kota->nama_kota }} - {{ $kota->kelas }} ({{ $kota->periode }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="anggota_kelompok">Anggota Kelompok <span class="text-danger">*</span></label>
                                <select name="anggota_kelompok[]" id="anggota_kelompok" 
                                    class="form-control select2 @error('anggota_kelompok') is-invalid @enderror" 
                                    multiple required>
                                    <option value="">-- Pilih Anggota --</option>
                                    <!-- Daftar anggota akan dimuat secara dinamis setelah memilih kota -->
                                </select>
                                <small class="form-text text-muted">
                                    Pilih 1-3 anggota kelompok (termasuk diri Anda)
                                </small>
                                @error('anggota_kelompok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="file_ta">File Tugas Akhir (PDF) <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" name="file_ta" id="file_ta" 
                                        class="custom-file-input @error('file_ta') is-invalid @enderror" 
                                        accept=".pdf" required>
                                    <label class="custom-file-label" for="file_ta">Pilih file...</label>
                                    @error('file_ta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    File harus dalam format PDF dan ukuran maksimal 10MB.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-primary" id="submitCreate">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Katalog TA
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Pilih anggota kelompok",
            maximumSelectionLength: 3
        });

        // Update file input label
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        // Load anggota kelompok berdasarkan kota yang dipilih
        $('#id_kota').on('change', function() {
            var idKota = $(this).val();
            if (idKota) {
                $.ajax({
                    url: '{{ route("katalog-ta.get-anggota") }}',
                    type: 'POST',
                    data: {
                        id_kota: idKota,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#anggota_kelompok').empty();
                        
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                var nim = value.nim ? value.nim : 'No NIM';
                                $('#anggota_kelompok').append('<option value="' + value.id + '">' + value.name + ' (' + nim + ')</option>');
                            });
                        } else {
                            // Jika tidak ada anggota untuk kota ini, tampilkan semua mahasiswa
                            loadAllMahasiswa();
                        }
                        
                        // Trigger change untuk memperbarui Select2
                        $('#anggota_kelompok').trigger('change');
                    },
                    error: function() {
                        console.error('Error loading anggota kelompok');
                        // Fallback ke semua mahasiswa jika terjadi error
                        loadAllMahasiswa();
                    }
                });
            } else {
                // Jika tidak ada kota yang dipilih, kosongkan select anggota
                $('#anggota_kelompok').empty().trigger('change');
            }
        });

        // Fungsi untuk memuat semua mahasiswa jika tidak ada anggota terkait
        function loadAllMahasiswa() {
            $.ajax({
                url: '{{ route("katalog-ta.get-all-mahasiswa") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#anggota_kelompok').empty();
                    
                    $.each(data, function(key, value) {
                        var nim = value.nim ? value.nim : 'No NIM';
                        $('#anggota_kelompok').append('<option value="' + value.id + '">' + value.name + ' (' + nim + ')</option>');
                    });
                    
                    $('#anggota_kelompok').trigger('change');
                }
            });
        }

        // Jika ada id_kota yang dipilih sebelumnya, muat anggota kelompok saat halaman dimuat
        var selectedKota = $('#id_kota').val();
        if (selectedKota) {
            $('#id_kota').trigger('change');
        }

        // Confirm dialog before submission
        $('#submitCreate').click(function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi Tambah',
                text: "Pastikan data TA sudah benar. Lanjutkan simpan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });
    });
</script>
@endpush
@endsection