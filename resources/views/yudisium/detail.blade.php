@extends('adminlte.layouts.app')

@section('title', 'Detail Yudisium Mahasiswa')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Profile dan Status Card -->
        <div class="row">
            <div class="col-md-4">
                <!-- Status Yudisium Card -->
                <div class="card card-primary content-wrapper">
                    <div class="card-header">
                        <h3 class="card-title">Status Yudisium</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @php
                                $kategoriClass = 'badge-secondary';
                                $kategoriText = 'Belum ditentukan';
                                
                                if(isset($yudisium->kategori_yudisium)) {
                                    switch($yudisium->kategori_yudisium) {
                                        case 1:
                                            $kategoriClass = 'badge-success';
                                            $kategoriText = 'Yudisium 1';
                                            break;
                                        case 2:
                                            $kategoriClass = 'badge-primary';
                                            $kategoriText = 'Yudisium 2';
                                            break;
                                        case 3:
                                            $kategoriClass = 'badge-warning';
                                            $kategoriText = 'Yudisium 3';
                                            break;
                                    }
                                }
                            @endphp
                            <span class="badge {{ $kategoriClass }} px-3 py-2" style="font-size: 1.2rem;">{{ $kategoriText }}</span>
                        </div>
                        
                        <p class="text-center">
                            @if(isset($yudisium->tanggal_yudisium))
                                Tanggal Yudisium: <strong>{{ \Carbon\Carbon::parse($yudisium->tanggal_yudisium)->format('d F Y') }}</strong>
                            @else
                                Tanggal Yudisium: <strong>Belum ditentukan</strong>
                            @endif
                        </p>
                        
                        <hr>
                        
                        <div class="mt-3">
                            <h5>Nilai Tugas Akhir</h5>
                            <div class="mb-2">
                                @php
                                    $nilaiAkhir = $yudisium->nilai_akhir ?? 0;
                                    $textClass = 'text-danger';

                                    if ($nilaiAkhir >= 85) {
                                        $textClass = 'text-success';
                                    } elseif ($nilaiAkhir >= 70) {
                                        $textClass = 'text-primary';
                                    } elseif ($nilaiAkhir >= 55) {
                                        $textClass = 'text-warning';
                                    }
                                @endphp

                                <p class="h4 font-weight-bold {{ $textClass }}">{{ $nilaiAkhir }}</p>
                            </div>
                            <p class="text-muted mb-0">Status: 
                                @if($yudisium->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                                @elseif($yudisium->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                                @elseif($yudisium->status == 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <!-- Tabs Navigasi -->
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="yudisium-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-detail" data-toggle="pill" href="#content-detail" role="tab" aria-controls="content-detail" aria-selected="true">Detail Yudisium</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-histori" data-toggle="pill" href="#content-histori" role="tab" aria-controls="content-histori" aria-selected="false">Histori Perubahan</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="yudisium-tabs-content">
                            <!-- Tab Detail -->
                            <div class="tab-pane fade show active" id="content-detail" role="tabpanel" aria-labelledby="tab-detail">
                                <h5 class="mb-3">Informasi Yudisium</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <th width="30%">KoTA</th>
                                                <td>{{ $yudisium->nama_kota }}</td>
                                            </tr>
                                            <tr>
                                                <th>Judul TA</th>
                                                <td>{{ $yudisium->judul }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kelas</th>
                                                <td>
                                                    @if($yudisium->kelas == '1') D3-A
                                                    @elseif($yudisium->kelas == '2') D3-B
                                                    @elseif($yudisium->kelas == '3') D4-A
                                                    @elseif($yudisium->kelas == '4') D4-B
                                                    @else {{ $yudisium->kelas }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Periode</th>
                                                <td>{{ $yudisium->periode }}</td>
                                            </tr>
                                            <tr>
                                                <th>Mahasiswa</th>
                                                <td>{{ $yudisium->mahasiswa }}</td>
                                            </tr>
                                            <tr>
                                                <th>Dosen Pembimbing</th>
                                                <td>{{ $yudisium->dosen }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Tab Histori -->
                            <div class="tab-pane fade" id="content-histori" role="tabpanel" aria-labelledby="tab-histori">
                                <h5 class="mb-3">Riwayat Perubahan Status Yudisium</h5>
                                
                                <div class="timeline">
                                    @forelse($logs as $log)
                                    <div class="time-label">
                                        <span class="bg-primary">{{ \Carbon\Carbon::parse($log->waktu_perubahan)->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-edit bg-blue"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($log->waktu_perubahan)->format('H:i') }}</span>
                                            <h3 class="timeline-header"><a href="#">{{ $log->nama_user }}</a> mengubah {{ $log->jenis_perubahan }}</h3>
                                            <div class="timeline-body">
                                                <p>Keterangan: {{ $log->keterangan }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                        <p class="text-muted">Belum ada riwayat perubahan status yudisium.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Catatan Tambahan -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Catatan Tambahan</h3>
                    </div>
                    <div class="card-body">
                        <div class="callout callout-warning">
                            <h5><i class="fas fa-exclamation-triangle mr-2"></i> Keterangan</h5>
                            <p>
                                @if(isset($yudisium->keterangan))
                                    {{ $yudisium->keterangan }}
                                @else
                                    Belum ada catatan tambahan untuk yudisium ini.
                                @endif
                            </p>
                        </div>
                        
                        <div class="mt-3">
                            <h5>Petunjuk Pengumpulan Berkas Yudisium:</h5>
                            <ol>
                                <li>Pastikan semua persyaratan telah terpenuhi sebelum tanggal yudisium.</li>
                                <li>Upload dokumen pendukung dalam format PDF dengan ukuran maksimal 5MB per file.</li>
                                <li>Penamaan file mengikuti format: NIM_NamaDokumen.pdf</li>
                                <li>Jika ada pertanyaan, silakan hubungi koordinator TA melalui email: koordinator.ta@example.ac.id</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">   
<style>
    .content-header {
        padding: 15px 0.5rem;
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
    }
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }
    .time-label {
        position: relative;
        margin-bottom: 15px;
        margin-left: 60px;
    }
    .time-label > span {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 4px;
        color: #fff;
        font-weight: 600;
    }
    .timeline > div {
        margin-bottom: 15px;
        position: relative;
    }
    .timeline > div > i {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
        color: #fff;
    }
    .timeline-item {
        box-shadow: 0 0 1px rgba(0, 0, 0, 0.1);
        border-radius: 3px;
        margin-left: 60px;
        margin-right: 15px;
        margin-top: 0;
        background: #fff;
        color: #444;
    }
    .timeline-item .time {
        float: right;
        color: #999;
        padding: 10px;
        font-size: 12px;
    }
    .timeline-item .timeline-header {
        margin: 0;
        padding: 10px;
        border-bottom: 1px solid #f4f4f4;
        font-size: 16px;
        font-weight: 600;
    }
    .timeline-item .timeline-body {
        padding: 10px;
    }
    .content-wrapper {
        padding-top: 20px;
    }
    .content-header {
        padding: 15px 0;
    }
</style>
@endsection

@section('js')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection