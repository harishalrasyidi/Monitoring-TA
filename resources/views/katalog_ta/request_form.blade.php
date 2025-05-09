@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="row mb-3">
            <div class="col-md-8">
                <h2>Request Katalog TA</h2>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('katalog-ta.show', $katalog->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Form Request: {{ $katalog->judul_ta }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Info:</strong> Email Anda (<strong>{{ Auth::user()->email }}</strong>) akan digunakan 
                            sebagai kontak untuk penulis TA. Penulis dapat menghubungi Anda secara langsung jika mereka bersedia
                            berbagi katalog TA.
                        </div>
                        
                        <form action="{{ route('katalog-ta.send-request', $katalog->id) }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="tujuan_request">Tujuan Request Katalog TA <span class="text-danger">*</span></label>
                                <textarea name="tujuan_request" id="tujuan_request" rows="5" 
                                    class="form-control @error('tujuan_request') is-invalid @enderror" 
                                    placeholder="Jelaskan tujuan Anda meminta katalog TA ini..." 
                                    required>{{ old('tujuan_request') }}</textarea>
                                @error('tujuan_request')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Jelaskan dengan jelas mengapa Anda memerlukan katalog TA ini. Hal ini akan membantu penulis dalam
                                    mempertimbangkan permintaan Anda.
                                </small>
                            </div>
                            
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="submitRequest">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Request
                                </button>
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
            
            Swal.fire({
                title: 'Konfirmasi Request',
                text: "Email request akan dikirim ke penulis TA. Lanjutkan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, kirim request',
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