@extends('adminlte.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col">
          <h1 class="m-0">Dashboard Koordinator</h1>
        </div><!-- /.col -->
        <div class="col d-flex justify-content-end">
        <div class="btn-group mr-2">
          <!-- Messages Dropdown Menu -->
          <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Periode
              </button>
              <ul class="dropdown-menu">
                  <li><a href="#" class="dropdown-item">2024</a></li>
                  <li><a href="#" class="dropdown-item">2023</a></li>
                </ul>
              </div>
            </div>
        <div class="btn-group mr-2">   
            <!-- Messages Dropdown Menu -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Kelas
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" class="dropdown-item">D3-A</a></li>
                    <li><a href="#" class="dropdown-item">D3-B</a></li>
                    <li><a href="#" class="dropdown-item">D4-A</a></li>
                    <li><a href="#" class="dropdown-item">D4-B</a></li>
                </ul>
            </div>
        </div>
        <div class="btn-group mr-2">
            <!-- Notifications Dropdown Menu -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" href="#">
                    Tahapan TA
                </button>
                <ul class="dropdown-menu dropdown-menu-lg">
                    <li><a href="#" class="dropdown-item">Seminar 1</a></li>
                    <li><a href="#" class="dropdown-item">Seminar 2</a></li>
                    <li><a href="#" class="dropdown-item">Seminar 3</a></li>
                    <li><a href="#" class="dropdown-item">Sidang</a></li>
                </ul>
            </div>
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
                <a href="{{ route('yudisium.index') }}" class="btn btn-app">
                  <i class="fas fa-graduation-cap"></i> Yudisium
                </a>
                <a href="{{ route('yudisium.dashboard') }}" class="btn btn-app">
                  <i class="fas fa-chart-pie"></i> Dashboard Yudisium
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
            <a href="{{ route('yudisium.index') }}" class="small-box-footer">
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

      <!-- Charts Row -->
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Distribusi Kategori Yudisium</h3>
            </div>
            <div class="card-body">
              <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Distribusi Progres Seminar</h3>
            </div>
            <div class="card-body">
              <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Status Pengumpulan Artefak</h3>
            </div>
            <div class="card-body">
              <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Status Yudisium</h3>
            </div>
            <div class="card-body">
              <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
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
                    <!-- Ini seharusnya diisi dari database -->
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
    </div>
  </section>

@push('scripts')
<script>
  $(function () {
    // Donut Chart untuk Distribusi Kategori Yudisium
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData = {
      labels: [
          'Yudisium 1',
          'Yudisium 2',
          'Yudisium 3',
      ],
      datasets: [
        {
          data: [12, 18, 10], // Disini seharusnya data dari database
          backgroundColor : ['#00a65a', '#f39c12', '#f56954'],
        }
      ]
    }
    var donutOptions = {
      maintainAspectRatio : false,
      responsive : true,
    }
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

    // Pie Chart untuk Distribusi Progres Seminar
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData = {
      labels: [
          'Seminar 1',
          'Seminar 2',
          'Seminar 3',
          'Sidang',
      ],
      datasets: [
        {
          data: [15, 10, 10, 5], // Disini seharusnya data dari database
          backgroundColor : ['#00c0ef', '#3c8dbc', '#d2d6de', '#f56954'],
        }
      ]
    }
    var pieOptions = {
      maintainAspectRatio : false,
      responsive : true,
    }
    new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

    // Bar Chart untuk Status Pengumpulan Artefak
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = {
      labels: ['FTA 01', 'FTA 02', 'FTA 03', 'FTA 04', 'FTA 05'],
      datasets: [
        {
          label: 'Sudah Mengumpulkan',
          backgroundColor: '#00a65a',
          data: [30, 28, 25, 20, 15] // Disini seharusnya data dari database
        },
        {
          label: 'Belum Mengumpulkan',
          backgroundColor: '#f56954',
          data: [10, 12, 15, 20, 25] // Disini seharusnya data dari database
        }
      ]
    }
    var barChartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      datasetFill: false
    }
    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

    // Stacked Bar Chart untuk Status Yudisium
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = {
      labels: ['D3-A', 'D3-B', 'D4-A', 'D4-B'],
      datasets: [
        {
          label: 'Approved',
          backgroundColor: '#00a65a',
          data: [5, 5, 5, 5] // Disini seharusnya data dari database
        },
        {
          label: 'Pending',
          backgroundColor: '#f39c12',
          data: [3, 3, 3, 3] // Disini seharusnya data dari database
        },
        {
          label: 'Rejected',
          backgroundColor: '#f56954',
          data: [2, 2, 2, 2] // Disini seharusnya data dari database
        }
      ]
    }
    var stackedBarChartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }
    new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
</script>
@endpush

@endsection