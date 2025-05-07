@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <h3>Detail File</h3>
        <div class="card">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>ID Kota:</strong> {{ $data->id_kota }}</li>
                    <li class="list-group-item"><strong>ID Artefak:</strong> {{ $data->id_artefak }}</li>
                    <li class="list-group-item"><strong>File:</strong> 
                        <a href="{{ asset('storage/' . $data->file_pengumpulan) }}" target="_blank">
                            Lihat File
                        </a>
                    </li>
                    <li class="list-group-item"><strong>Waktu Pengumpulan:</strong> {{ $data->waktu_pengumpulan }}</li>
                </ul>
                <a href="{{ route('katalog.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
