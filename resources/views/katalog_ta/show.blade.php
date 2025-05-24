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
                @if(!$kota->users->contains('id', Auth::user()->id))
                    <a href="{{ route('katalog-ta.request-form', $kota->id_kota) }}" class="btn btn-primary">
                        <i class="fas fa-envelope mr-1"></i> Request TA
                    </a>
                @else
                    @if($artefakKota->count() > 0)
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-download mr-1"></i> Download Artefak
                            </button>
                            <div class="dropdown-menu">
                                @foreach($artefakKota as $artefak)
                                    <a class="dropdown-item" href="{{ route('katalog-ta.download-artefak', [$kota->id_kota, $artefak->id_artefak]) }}">
                                        <i class="fas fa-file-pdf mr-1"></i> {{ $artefak->nama_artefak }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
                <h4 class="mb-0">{{ $kota->judul }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4 text-center">
                        <i class="fas fa-users fa-5x text-primary"></i>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px">Nama KoTA</th>
                                <td>{{ $kota->nama_kota }}</td>
                            </tr>
                            <tr>
                                <th>Judul TA</th>
                                <td>{{ $kota->judul }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td><span class="badge badge-success">{{ $kota->kelas }}</span></td>
                            </tr>
                            <tr>
                                <th>Periode</th>
                                <td><span class="badge badge-primary">{{ $kota->periode }}</span></td>
                            </tr>
                            @if($kota->deskripsi)
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $kota->deskripsi }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Anggota Mahasiswa</th>
                                <td>
                                    @if($mahasiswa->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach($mahasiswa as $mhs)
                                                <li class="list-group-item px-0">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-graduate mr-2 text-primary"></i>
                                                        <div>
                                                            <strong>{{ $mhs->name }}</strong>
                                                            @if($mhs->nomor_induk)
                                                                <span class="text-muted">({{ $mhs->nomor_induk }})</span>
                                                            @endif
                                                            <br>
                                                            <small class="text-muted">{{ $mhs->email }}</small>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Tidak ada data mahasiswa</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Dosen Pembimbing</th>
                                <td>
                                    @if($dosen->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach($dosen as $dsn)
                                                <li class="list-group-item px-0">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-chalkboard-teacher mr-2 text-warning"></i>
                                                        <div>
                                                            <strong>{{ $dsn->name }}</strong><br>
                                                            <small class="text-muted">{{ $dsn->email }}</small>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Tidak ada data dosen pembimbing</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Section Artefak yang telah dikumpulkan -->
                    @if($artefakKota->count() > 0)
                        <div class="col-md-12 mt-4">
                            <h5><i class="fas fa-file-archive mr-2"></i>Artefak yang Telah Dikumpulkan</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Nama Artefak</th>
                                            <th>Tanggal Pengumpulan</th>
                                            @if($kota->users->contains('id', Auth::user()->id) || Auth::user()->role == 1)
                                                <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($artefakKota as $artefak)
                                            <tr>
                                                <td>
                                                    <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                    {{ $artefak->nama_artefak }}
                                                </td>
                                                <td>
                                                    @if($artefak->waktu_pengumpulan)
                                                        {{ \Carbon\Carbon::parse($artefak->waktu_pengumpulan)->format('d M Y, H:i') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                @if($kota->users->contains('id', Auth::user()->id) || Auth::user()->role == 1)
                                                    <td>
                                                        @if($artefak->file_pengumpulan)
                                                            <a href="{{ route('katalog-ta.download-artefak', [$kota->id_kota, $artefak->id_artefak]) }}" 
                                                               class="btn btn-sm btn-success">
                                                                <i class="fas fa-download"></i> Download
                                                            </a>
                                                        @else
                                                            <span class="text-muted">File tidak tersedia</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Section Progress/Status Tahapan -->
                    @if($tahapanProgres->count() > 0)
                        <div class="col-md-12 mt-4">
                            <h5><i class="fas fa-tasks mr-2"></i>Progress Tahapan</h5>
                            <div class="row">
                                @foreach($tahapanProgres as $tahapan)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border-left-{{ $tahapan->status == 1 ? 'success' : 'secondary' }}">
                                            <div class="card-body py-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-{{ $tahapan->status == 1 ? 'check-circle text-success' : 'clock text-muted' }} mr-2"></i>
                                                    <div>
                                                        <div class="text-sm font-weight-bold">{{ $tahapan->nama_progres }}</div>
                                                        <div class="text-xs text-muted">
                                                            {{ $tahapan->status == 1 ? 'Selesai' : 'Belum Selesai' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Info untuk non-anggota -->
                    @if(!$kota->users->contains('id', Auth::user()->id))
                        <div class="col-md-12 mt-4">
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Info:</strong> Untuk mengakses artefak lengkap dari TA ini, silakan gunakan tombol "Request TA" di atas.
                                Anggota KoTA akan menerima notifikasi dan dapat menghubungi Anda melalui email yang terdaftar.
                            </div>
                        </div>
                    @endif

                    <!-- Info untuk anggota -->
                    @if($kota->users->contains('id', Auth::user()->id))
                        <div class="col-md-12 mt-4">
                            <div class="alert alert-info">
                                <i class="fas fa-user-check mr-2"></i>
                                <strong>Selamat datang!</strong> Anda adalah anggota dari KoTA ini. 
                                Anda dapat mengunduh semua artefak yang telah dikumpulkan.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Tooltip initialization
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush