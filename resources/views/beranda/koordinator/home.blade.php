@extends('adminlte.layouts.app')

@section('content')

<div class="content-wrapper">
  <h3 class="mb-4">Dashboard Koordinator TA</h3>
  <hr>

  <!-- Kartu Ringkasan -->
  <div class="row text-center">
    <!-- Card Total KoTA -->
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-users fa-2x text-primary mb-2"></i>
          <h6>Total KoTA</h6>
          <h3>{{ $totalKota }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
          <h6>Selesai Semua Tahapan</h6>
          <h3>{{ $selesai }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-spinner fa-2x text-warning mb-2"></i>
          <h6>Dalam Progres</h6>
          <h3>{{ $dalamProgres }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="d-flex flex-column align-items-start mt-3">
        <button type="button" class="btn btn-success mb-2 btn-yudisium" data-kategori="1">Yudisium 1</button>
        <button type="button" class="btn btn-warning mb-2 btn-yudisium" data-kategori="2">Yudisium 2</button>
        <button type="button" class="btn btn-danger mb-2 btn-yudisium" data-kategori="3">Yudisium 3</button>
      </div>
    </div>
  </div>

  <!-- Statistik Tahapan KoTA -->
  <div class="card mt-4">
    <div class="card-header">
      <strong>Statistik Status Tahapan KoTA</strong>
      <form method="GET" id="filterForm" class="form-inline float-right">
        <label class="mr-2">Tahun:</label>
        <select name="periode" class="form-control mr-2" onchange="this.form.submit()">
          <option value="">Semua</option>
          @foreach($periodes as $periode)
            <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>{{ $periode }}</option>
          @endforeach
        </select>
        <label class="mr-2">Kelas:</label>
        <select name="kelas" class="form-control mr-2" onchange="this.form.submit()">
          <option value="">Semua</option>
          @foreach($kelasList as $kelas)
            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
          @endforeach
        </select>
        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
      </form>
    </div>
    <div class="card-body">
      <div id="tahapanChart"></div>
      {{-- <pre>{{ var_dump($chartData) }}</pre> --}}
    </div>
  </div>

  <!-- List KoTA -->
  <div class="card mt-4">
    <div class="card-header">
      <strong>List KoTA</strong>
    </div>
    <div class="card-body table-responsive">
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
              <td>{{ $no+1 }}</td>
              <td>
                <strong>{{ $kota->nama_kota }}</strong><br>
                <small>{{ $kota->judul }}</small>
              </td>
              @php
                $tahapan = $kota->tahapanProgress->sortBy('id_master_tahapan_progres');
                $status = [];
                foreach($tahapan as $tp) {
                  $status[] = $tp->status;
                }
                for($i = count($status); $i < 4; $i++) $status[] = '-';
              @endphp
              <td>{{ $status[0] ?? '-' }}</td>
              <td>{{ $status[1] ?? '-' }}</td>
              <td>{{ $status[2] ?? '-' }}</td>
              <td>{{ $status[3] ?? '-' }}</td>
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

<script>
  document.addEventListener("DOMContentLoaded", function () {
    var options = {
      chart: { type: 'bar', height: 350 },
      series: [{
        name: 'Jumlah Mahasiswa',
        data: {!! json_encode(array_values($chartData)) !!}
      }],
      xaxis: {
        categories: {!! json_encode(array_keys($chartData)) !!}
      },
      colors: ['#28a745', '#ffc107', '#dc3545', '#007bff']
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

        fetch(`/koordinator/yudisium-list?kategori=${kategori}&periode=${periode}&kelas=${kelas}`)
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


