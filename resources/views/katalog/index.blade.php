@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1 class="m-0">Katalog Laporan Tugas Akhir</h1>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Search Box -->
            <div class="row mb-4">
                <div class="col-md-6 offset-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari judul atau penulis...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="row" id="reportCards">
                @foreach($laporanList as $item)
                    @php
                        // Get authors for this thesis
                        $penulis = \App\Models\User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                            ->where('tbl_kota_has_user.id_kota', $item->id_kota)
                            ->where('users.role', 3)
                            ->pluck('name')
                            ->first();
                    @endphp
                    
                    <div class="col-md-4 mb-4 report-card">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0 text-truncate" title="{{ $item->judul }}">
                                    {{ \Illuminate\Support\Str::limit($item->judul, 40) }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 text-center">
                                    <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                </div>
                                <p><strong>Penulis:</strong> {{ $penulis ?? 'Tidak ada data' }}</p>
                                <p><strong>Tahun:</strong> {{ $item->periode }}</p>
                                <p><strong>Program Studi:</strong> {{ $item->kelas }}</p>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Dikumpulkan: {{ \Carbon\Carbon::parse($item->waktu_pengumpulan)->format('d M Y') }}</small>
                                    <a href="{{ route('laporan.show', $item->id_kota) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if(count($laporanList) == 0)
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-5x text-secondary mb-3"></i>
                    <h4 class="text-muted">Belum ada laporan tugas akhir</h4>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple client-side search functionality
    $(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#reportCards .report-card").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
@endsection