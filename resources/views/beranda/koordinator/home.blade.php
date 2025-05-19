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
          <h3>120</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
          <h6>Selesai Semua Tahapan</h6>
          <h3>73</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-spinner fa-2x text-warning mb-2"></i>
          <h6>Dalam progres</h6>
          <h3>26</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-exclamation-circle fa-2x text-danger mb-2"></i>
          <h6>Terlambat</h6>
          <h3>20</h3>
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
            <th>Revisi</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Kelompok 1</td>
            <td>Tuntas</td>
            <td>Tuntas</td>
            <td>Tuntas</td>
            <td>Tuntas</td>
            <td><span class="badge badge-success">Selesai</span></td>
          </tr>
          <tr>
            <td>2</td>
            <td>Kelompok 2</td>
            <td>Belum Tuntas</td>
            <td>Belum Tuntas</td>
            <td>Belum Tuntas</td>
            <td>Belum Tuntas</td>
            <td><span class="badge badge-danger">Terlambat</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var options = {
      chart: {
        type: 'bar',
        height: 350
      },
      series: [{
        name: 'Jumlah Mahasiswa',
        data: [95, 80, 50, 40]
      }],
      xaxis: {
        categories: ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang']
      },
      colors: ['#28a745', '#ffc107', '#dc3545']
    };

    var chart = new ApexCharts(document.querySelector("#tahapanChart"), options);
    chart.render();
  });
</script>
@endpush
