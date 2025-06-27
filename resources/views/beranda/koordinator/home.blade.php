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
              <div class="rounded bg-success d-flex align-items-center justify-content-center mr-3 btn-yudisium" style="width:48px;height:48px; cursor:pointer;" data-kategori="1">
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
              <div class="rounded bg-warning d-flex align-items-center justify-content-center mr-3 btn-yudisium" style="width:48px;height:48px; cursor:pointer;" data-kategori="2">
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
              <div class="rounded bg-danger d-flex align-items-center justify-content-center mr-3 btn-yudisium" style="width:48px;height:48px; cursor:pointer;" data-kategori="3">
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

  <!-- List KoTA Bimbingan -->
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

@include('beranda.koordinator.yudisium_modal')

@endsection

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
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
          return val + '/' + totalKota + '(' + persenStr + '%)';
        }
      },
      tooltip: {
        y: {
          formatter: function (val, opts) {
            return val + ' KoTA';
          }
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