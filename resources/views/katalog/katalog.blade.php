@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1>Daftar Pengumpulan File</h1>
            <hr>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($katalog as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                <h5 class="card-title">File {{ $item->id_kota }}</h5>
                                <p class="card-text small">{{ \Illuminate\Support\Str::limit($item->file_pengumpulan, 30) }}</p>
                                <a href="{{ route('katalog.show', $item->id) }}" class="btn btn-info">Detail</a>
                            </div>
                            <div class="card-footer text-muted text-center">
                                {{ $item->waktu_pengumpulan }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
