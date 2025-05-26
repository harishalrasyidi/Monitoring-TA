@extends('adminlte.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col">
          <h1 class="m-0">Dashboard Yudisium</h1>
        </div><!-- /.col -->
        <div class="col d-flex justify-content-end">
          <div class="btn-group mr-2">
            <a href="{{ route('yudisium.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->
      <hr>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Filter Section -->
      <div class="card mb-4">
        <div class="card-header">
          <h3 class="card-title">Filter Data</h3>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('yudisium.dashboard') }}" class="row">
            <div class="col-md-4 form-group">
              <label>Periode</label>
              <select name="periode" class="form-control">
                <option value="">Semua Periode</option>
                @foreach($periodeList as $p)
                <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4 form-group">
              <label>Kelas</label>
              <select name="kelas" class="form-control">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ $kelas == $k ? 'selected' : '' }}>
                  @if($k == '1') D3-A
                  @elseif($k == '2') D3-B
                  @elseif($k == '3') D4-A
                  @elseif($k == '4') D4-B
                  @else {{ $k }}
                  @endif
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label>&nbsp;</label>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('yudisium.dashboard') }}" class="btn btn-default">Reset</a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Cards -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $totalYudisium }}</h3>
              <p>Total Yudisium</p>
            </div>
            <div class="icon">
              <i class="fas fa-graduation-cap"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $totalKota }}</h3>
              <p>Total KoTA</p>
            </div>
            <div class="icon">
              <i class="fas fa-book"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $belumYudisium }}</h3>
              <p>Belum Yudisium</p>
            </div>
            <div class="icon">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ number_format($rataRataNilai, 2) }}</h3>
              <p>Rata-rata Nilai</p>
            </div>
            <div class="icon">
              <i class="fas fa-chart-line"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Distribusi Kategori Yudisium</h3>
            </div>
            <div class="card-body">
              <canvas id="kategoriChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Distribusi Status Yudisium</h3>
            </div>
            <div class="card-body">
              <canvas id="statusChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Distribusi Kelas</h3>
            </div>
            <div class="card-body">
              <canvas id="kelasChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@push('scripts')
<script>
  $(function() {
    // Kategori Chart
    var kategoriData = {
      labels: [
        @foreach($distribusiKategori as $d)
        'Yudisium {{ $d->kategori_yudisium }}',
        @endforeach
      ],
      datasets: [{
        data: [
          @foreach($distribusiKategori as $d)
          {{ $d->jumlah }},
          @endforeach
        ],
        backgroundColor: ['#f56954', '#00a65a', '#f39c12']
      }]
    };
    
    var kategoriCanvas = $('#kategoriChart').get(0).getContext('2d');
    new Chart(kategoriCanvas, {
      type: 'pie',
      data: kategoriData,
      options: {
        maintainAspectRatio: false,
        responsive: true,
      }
    });
    
    // Status Chart
    var statusData = {
      labels: [
        @foreach($distribusiStatus as $d)
        '{{ ucfirst($d->status) }}',
        @endforeach
      ],
      datasets: [{
        data: [
          @foreach($distribusiStatus as $d)
          {{ $d->jumlah }},
          @endforeach
        ],
        backgroundColor: ['#f39c12', '#00a65a', '#f56954']
      }]
    };
    
    var statusCanvas = $('#statusChart').get(0).getContext('2d');
    new Chart(statusCanvas, {
      type: 'doughnut',
      data: statusData,
      options: {
        maintainAspectRatio: false,
        responsive: true,
      }
    });
    
    // Kelas Chart
    var kelasData = {
      labels: [
        @foreach($distribusiKelas as $d)
        '{{ $d->kelas == "1" ? "D3-A" : ($d->kelas == "2" ? "D3-B" : ($d->kelas == "3" ? "D4-A" : "D4-B")) }}',
        @endforeach
      ],
      datasets: [{
        label: 'Jumlah',
        data: [
          @foreach($distribusiKelas as $d)
          {{ $d->jumlah }},
          @endforeach
        ],
        backgroundColor: '#3c8dbc'
      }]
    };
    
    var kelasCanvas = $('#kelasChart').get(0).getContext('2d');
    new Chart(kelasCanvas, {
      type: 'bar',
      data: kelasData,
      options: {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });
  });
</script>
@endpush

@endsection