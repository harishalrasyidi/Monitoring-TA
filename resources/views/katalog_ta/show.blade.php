@extends('adminlte.layouts.app')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="row mb-3">
            <div class="col-md-8">
                <h2>Detail Katalog Tugas Akhir</h2>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('katalog-ta.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                @if(!Auth::user()->isPenulisTA($katalog->id))
                    <a href="{{ route('katalog-ta.request-form', $katalog->id) }}" class="btn btn-primary">
                        <i class="fas fa-envelope mr-1"></i> Request TA
                    </a>
                @else
                    <a href="{{ route('katalog-ta.download', $katalog->id) }}" class="btn btn-success">
                        <i class="fas fa-download mr-1"></i> Download TA
                    </a>
                    <a href="{{ route('katalog-ta.edit', $katalog->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                @endif
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
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">{{ $katalog->judul_ta }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4 text-center">
                        <i class="fas fa-file-pdf fa-5x text-danger"></i>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px">Kota</th>
                                <td>{{ $katalog->kota->nama_kota ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>{{ $katalog->kota->kelas ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Periode</th>
                                <td>{{ $katalog->kota->periode ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pengumpulan</th>
                                <td>{{ \Carbon\Carbon::parse($katalog->waktu_pengumpulan)->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $katalog->deskripsi }}</td>
                            </tr>
                            <tr>
                                <th>Anggota Tim</th>
                                <td>
                                    @if($katalog->anggota->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach($katalog->anggota as $anggota)
                                                <li class="list-group-item px-0">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-graduate mr-2 text-primary"></i>
                                                        <div>
                                                            <strong>{{ $anggota->user->name }}</strong><br>
                                                            <small class="text-muted">{{ $anggota->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Tidak ada data anggota</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    @if(!Auth::user()->isPenulisTA($katalog->id))
                        <div class="col-md-12 mt-4">
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Info:</strong> Untuk mengakses file lengkap dari TA ini, silakan gunakan tombol "Request TA" di atas.
                                Penulis TA akan menerima notifikasi dan dapat menghubungi Anda melalui email yang terdaftar.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection