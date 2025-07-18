@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper p-4">
    <h3 class="mb-4">
        @if (auth()->user()->role == 1)
            Dashboard Koordinator TA
            @if(isset($kelasList) && isset($kelasLabels) && count($kelasList))
                <span class="h6 text-muted">(Kelas: {{ collect($kelasList)->map(fn($k)=>($kelasLabels[$k]??$k))->implode(', ') }})</span>
            @endif
        @elseif (auth()->user()->role == 5)
            Dashboard Kaprodi D3
        @elseif (auth()->user()->role == 4)
            Dashboard Kaprodi D4
        @endif
    </h3>
    <hr>

    @if(auth()->user()->role == 4 || auth()->user()->role == 5)
    <div class="mb-4">
        <form method="GET" id="filterForm" class="form-inline">
            <label class="mr-2">Tahun:</label>
            <select name="periode" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($periodes as $periode)
                    <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>{{ $periode }}</option>
                @endforeach
            </select>
            <label class="mr-2">Kelas:</label>
            @php
                $kelasLabels = [
                    1 => 'D3 - A',
                    2 => 'D3 - B',
                    3 => 'D4 - A',
                    4 => 'D4 - B',
                ];
            @endphp

            <select name="kelas" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                        {{ $kelasLabels[$kelas] ?? $kelas }}
                    </option>
                @endforeach
            </select>

            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        </form>
    </div>
    @endif

    <!-- Kartu Ringkasan -->
    <div class="row text-center align-items-stretch">
        <!-- Card Total KoTA -->
        <div class="col-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h6>Total KoTA</h6>
                    <h3>{{ $totalKota }}</h3>
                </div>
            </div>
        </div>  
        <!-- Card Selesai Semua Tahapan -->
        <div class="col-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h6>Selesai Semua Tahapan</h6>
                    <h3>{{ $selesai }}</h3>
                </div>
            </div>
        </div>
        <!-- Card Dalam Progres -->
        <div class="col-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="fas fa-spinner fa-2x text-warning mb-2"></i>
                    <h6>Dalam Progres</h6>
                    <h3>{{ $dalamProgres }}</h3>
                </div>
            </div>
        </div>
        <!-- Card Yudisium -->
        <div class="col-3 mb-3">
            <div class="card h-100">
                <div class="card-body p-3 d-flex flex-column justify-content-center">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center border-0">
                            <div class="rounded bg-success d-flex align-items-center justify-content-center mr-3 btn-yudisium"
                                style="width:48px;height:48px; cursor:pointer;" data-kategori="1">
                                <i class="fas fa-graduation-cap fa-lg text-white"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold">Yudisium 1</div>
                                <div class="text-muted small">
                                    <div><i class="fas fa-users"></i> Total: {{ $totalYudisium1 }}</div>
                                    @if($totalKota > 0)
                                        <span class="text-success mr-2">{{ number_format(($totalYudisium1 / $totalKota) * 100, 1) }}%</span>
                                    @endif
                                    <span class="text-info">{{ $totalYudisium1 }}/{{ $totalKota - ($totalYudisium1 + $totalYudisium2 + $totalYudisium3) }} KoTA</span>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center border-0">
                            <div class="rounded bg-warning d-flex align-items-center justify-content-center mr-3 btn-yudisium"
                                style="width:48px;height:48px; cursor:pointer;" data-kategori="2">
                                <i class="fas fa-graduation-cap fa-lg text-white"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold">Yudisium 2</div>
                                <div class="text-muted small">
                                    <div><i class="fas fa-users"></i> Total: {{ $totalYudisium2 }}</div>
                                    @if($totalKota > 0)
                                        <span class="text-success mr-2">{{ number_format(($totalYudisium2 / $totalKota) * 100, 1) }}%</span>
                                    @endif
                                    <span class="text-info">{{ $totalYudisium2 }}/{{ $totalKota - ($totalYudisium1 + $totalYudisium2 + $totalYudisium3) }} KoTA</span>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center border-0">
                            <div class="rounded bg-danger d-flex align-items-center justify-content-center mr-3 btn-yudisium"
                                style="width:48px;height:48px; cursor:pointer;" data-kategori="3">
                                <i class="fas fa-graduation-cap fa-lg text-white"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold">Yudisium 3</div>
                                <div class="text-muted small">
                                    <div><i class="fas fa-users"></i> Total: {{ $totalYudisium3 }}</div>
                                    @if($totalKota > 0)
                                        <span class="text-success mr-2">{{ number_format(($totalYudisium3 / $totalKota) * 100, 1) }}%</span>
                                    @endif
                                    <span class="text-info">{{ $totalYudisium3 }}/{{ $totalKota - ($totalYudisium1 + $totalYudisium2 + $totalYudisium3) }} KoTA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Quick Access Buttons -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Menu Cepat</h3>
                        </div>
                        <div class="card-body">
                            <div class="btn-group">
                                <a href="{{ route('kota') }}" class="btn btn-app">
                                    <i class="fas fa-book"></i> Data KoTA
                                </a>
                                <a href="{{ route('timeline') }}" class="btn btn-app">
                                    <i class="fas fa-calendar-alt"></i> Timeline
                                </a>
                                <a href="{{ route('artefak') }}" class="btn btn-app">
                                    <i class="fas fa-file-alt"></i> Artefak
                                </a>
                                <a href="{{ route('yudisium.kelola') }}" class="btn btn-app">
                                    <i class="fas fa-graduation-cap"></i> Yudisium
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics Row -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalKoTA ?? '40' }}</h3>
                            <p>Total KoTA</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('kota') }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalSidang ?? '25' }}</h3>
                            <p>Sidang Selesai</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalYudisium ?? '30' }}</h3>
                            <p>Total Yudisium</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <a href="{{ route('yudisium.kelola') }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $belumYudisium ?? '10' }}</h3>
                            <p>Belum Yudisium</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Updates -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pembaruan Yudisium Terbaru</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>KoTA</th>
                                            <th>Mahasiswa</th>
                                            <th>Kategori</th>
                                            <th>Status</th>
                                            <th>Tanggal Update</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>101</td>
                                            <td>Nama Mahasiswa</td>
                                            <td>Yudisium 1</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                            <td>{{ now()->format('d/m/Y') }}</td>
                                            <td><a href="#" class="btn btn-sm btn-info">Detail</a></td>
                                        </tr>
                                        <tr>
                                            <td>102</td>
                                            <td>Nama Mahasiswa</td>
                                            <td>Yudisium 2</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td>{{ now()->format('d/m/Y') }}</td>
                                            <td><a href="#" class="btn btn-sm btn-info">Detail</a></td>
                                        </tr>
                                        <tr>
                                            <td>103</td>
                                            <td>Nama Mahasiswa</td>
                                            <td>Yudisium 3</td>
                                            <td><span class="badge badge-danger">Rejected</span></td>
                                            <td>{{ now()->format('d/m/Y') }}</td>
                                            <td><a href="#" class="btn btn-sm btn-info">Detail</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Tahapan KoTA -->
            <div class="card mt-4">
                <div class="card-header">
                    <strong>Statistik Status Tahapan KoTA</strong>
                </div>
                <div class="card-body">
                    <div id="tahapanChart"></div>
                    {{-- <pre>{{ var_dump($chartData) }}</pre> --}}
                </div>
            </div>

            <!-- List KoTA -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <strong class="mb-2 mb-md-0">List KoTA</strong>

                        <!-- Search Form -->
                        <form method="GET" 
                            action="{{ auth()->user()->role == 1 ? route('koordinator.dashboard') : route('kaprodi.dashboard') }}" 
                            class="d-flex align-items-center w-100" 
                            style="max-width: 400px;">
                        
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control form-control-lg mr-2" 
                                placeholder="Cari nama kota atau judul..." 
                                value="{{ request('search') }}" 
                                style="flex: 1;"
                            >
                            <button type="submit" class="btn btn-lg btn-primary">
                                <i class="fas fa-search"></i>
                            </button>

                            <!-- 🔁 Tambahkan hidden input agar filter tidak hilang -->
                            <input type="hidden" name="periode" value="{{ request('periode') }}">
                            <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        </form>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    @if(request('search'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle"></i>
                            Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                            <small class="ml-2 text-muted">({{ $kotaList->total() }} hasil ditemukan)</small>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kelompok</th>
                                <th>Seminar 1</th>
                                <th>Seminar 2</th>
                                <th>Seminar 3</th>
                                <th>Sidang</th>
                                <th>Status</th>
                                <th>Artefak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kotaList as $no => $kota)
                                <tr>
                                    <td>{{ $kotaList->firstItem() + $no }}</td>
                                    <td>
                                        <strong>{{ $kota->nama_kota }}</strong><br>
                                        <small class="text-muted">{{ $kota->judul }}</small>
                                    </td>
                                    @php
                                        $tahapan = $kota->tahapanProgress->sortBy('id_master_tahapan_progres');
                                        $status = [];
                                        foreach($tahapan as $tp) {
                                            $status[] = $tp->status;
                                        }
                                        for($i = count($status); $i < 4; $i++) $status[] = '-';
                                    @endphp
                                    <td>
                                        @if(($status[0] ?? '-') === 'selesai')
                                            <span class="badge badge-success">Tuntas</span>
                                        @elseif(($status[0] ?? '-') === 'progress')
                                            <span class="badge badge-warning">Progress</span>
                                        @else
                                            <span class="badge badge-secondary">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($status[1] ?? '-') === 'selesai')
                                            <span class="badge badge-success">Tuntas</span>
                                        @elseif(($status[1] ?? '-') === 'progress')
                                            <span class="badge badge-warning">Progress</span>
                                        @else
                                            <span class="badge badge-secondary">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($status[2] ?? '-') === 'selesai')
                                            <span class="badge badge-success">Tuntas</span>
                                        @elseif(($status[2] ?? '-') === 'progress')
                                            <span class="badge badge-warning">Progress</span>
                                        @else
                                            <span class="badge badge-secondary">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($status[3] ?? '-') === 'selesai')
                                            <span class="badge badge-success">Tuntas</span>
                                        @elseif(($status[3] ?? '-') === 'progress')
                                            <span class="badge badge-warning">Progress</span>
                                        @else
                                            <span class="badge badge-secondary">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $last = $kota->tahapanProgress->sortByDesc('id_master_tahapan_progres')->first();
                                        @endphp
                                        @if($last && $last->status === 'selesai' && optional($last->masterTahapan)->nama_progres === 'Sidang')
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-warning">Dalam Progres</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('kota.artefak.detail', $kota->id_kota) }}" class="btn btn-sm btn-primary">
                                            Lihat Detail
                                        </a> 
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="d-flex align-items-center">
                            <span class="mr-2">Tampilkan</span>
                            <select class="form-control form-control-sm" id="perPage" style="width: 70px">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="ml-2">data per halaman</span>
                        </div>
                        <div>
                            {{ $kotaList->appends(['per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('beranda.koordinator.yudisium_modal')
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const timelineData = @json($timelineData);

        var options = {
            chart: { type: 'bar', height: 350 },
            series: [{
                name: 'Jumlah KoTA',
                data: {!! json_encode($chartDataJumlah) !!}
            }],
            xaxis: {
                categories: {!! json_encode(array_keys($chartData)) !!}
            },
            colors: ['#28a745'],
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    var totalKota = {{ $totalKota }};
                    var persen = {!! json_encode($chartDataPersen) !!}[opts.dataPointIndex];
                    var persenStr = persen.toString().replace('.', ',');
                    return val + '/' + totalKota + ' KoTA (' + persenStr + '%)';
                }
            },
            tooltip: {
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    const tahapanName = w.globals.labels[dataPointIndex];
                    const kotaCount = series[seriesIndex][dataPointIndex];
                    const timeline = timelineData.find(item => item.name === tahapanName);
                    let timelineInfo = '';
                    if (timeline) {
                        const startDate = new Date(timeline.start).toLocaleDateString('id-ID', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        });
                        const endDate = new Date(timeline.end).toLocaleDateString('id-ID', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        });
                        timelineInfo = `
                            <div style="border-top: 1px solid #e0e0e0; margin-top: 8px; padding-top: 8px;">
                                <div style="font-size: 11px; color: #666; margin-bottom: 2px;">
                                    <strong>📅 Timeline:</strong>
                                </div>
                                <div style="font-size: 15px; color: #888;">
                                    <div>🟢 Mulai: ${startDate}</div>
                                    <div>🔴 Selesai: ${endDate}</div>
                                </div>
                            </div>
                        `;
                    }
                    return `
                        <div style="
                            background: #fff; 
                            border: 1px solid #e0e0e0; 
                            border-radius: 6px; 
                            padding: 12px; 
                            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                            min-width: 180px;
                        ">
                            <div style="font-weight: 600; color: #333; margin-bottom: 4px;">
                                ${tahapanName}
                            </div>
                            <div style="color: #28a745; font-size: 14px; font-weight: 500;">
                                ${Math.floor(kotaCount)} KoTA Selesai
                            </div>
                            ${timelineInfo}
                        </div>
                    `;
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#tahapanChart"), options);
        chart.render();

        document.getElementById('perPage').addEventListener('change', function() {
            var url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });

        document.querySelectorAll('.btn-yudisium').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var kategori = this.getAttribute('data-kategori');
                var periode = "{{ request('periode') }}";
                var kelas = "{{ request('kelas') }}";
                document.getElementById('modalYudisiumType').innerText = kategori;

                let yudisiumUrl = '/koordinator/yudisium-list';
                @if(auth()->user()->role == 4 || auth()->user()->role == 5)
                    yudisiumUrl = '/kaprodi/yudisium-list';
                @endif
                fetch(`${yudisiumUrl}?kategori=${kategori}&periode=${periode}&kelas=${kelas}`)
                    .then(response => response.json())
                    .then(data => {
                        var tbody = document.getElementById('modalYudisiumTableBody');
                        tbody.innerHTML = '';
                        if (data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="2" class="text-center">Tidak ada data</td></tr>';
                        } else {
                            data.forEach(function(item) {
                                var row = `<tr><td>${item.nama_kota}</td><td>${item.judul}</td></tr>`;
                                tbody.innerHTML += row;
                            });
                        }
                        $('#modalYudisiumList').modal('show');
                    });
            });
        });
    });
</script>
@endpush