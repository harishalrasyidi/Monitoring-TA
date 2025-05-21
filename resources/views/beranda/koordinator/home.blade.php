@extends('adminlte.layouts.app')

@section('content')

<div class="content-wrapper">
  <h3 class="mb-4">Dashboard Koordinator TA</h3>
  <hr>

  <!-- Kartu Ringkasan -->
  <div class="row text-center">
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
          <h6>Dalam progres</h6>
          <h3>{{ $dalamProgres }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-exclamation-circle fa-2x text-danger mb-2"></i>
          <h6>Terlambat</h6>
          <h3>{{ $terlambat }}</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik Tahapan KoTA -->
  <div class="card mt-4">
    <div class="card-header">
      <strong>Statistik Status Tahapan KoTA</strong>
      <div class="float-right">
        <select class="form-control d-inline w-auto mr-2">
          <option>Tahun</option>
        </select>
        <select class="form-control d-inline w-auto">
          <option>Kelas</option>
        </select>
      </div>
    </div>
    <div class="card-body">
      <div id="tahapanChart"></div>
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
                // Urutkan progres berdasarkan id_master_tahapan_progres
                $tahapan = $kota->tahapanProgress->sortBy('id_master_tahapan_progres');
                $status = [];
                foreach($tahapan as $tp) {
                  $status[] = $tp->status;
                }
                // Jika tahapan kurang dari 4, tambahkan '-'
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
                @if($last && $last->status === 'tuntas' && optional($last->masterTahapan)->nama_progres === 'Sidang')
                  <span class="badge badge-success">Selesai</span>
                @elseif($last && $last->status === 'belum tuntas')
                  <span class="badge badge-danger">Terlambat</span>
                @else
                  <span class="badge badge-warning">Dalam Progres</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center">Data tidak ditemukan</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
  });
</script>
@endpush
