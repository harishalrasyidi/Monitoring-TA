@extends('adminlte.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col">
          <h1 class="m-0">Dashboard Kaprodi</h1>
        </div><!-- /.col -->
        <div class="col d-flex justify-content-end">
        <div class="btn-group mr-2">
            <!-- Messages Dropdown Menu -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Periode
                </button>
                <ul class="dropdown-menu">
                  <li><a href="{{ route('kota', ['sort' => 'periode', 'direction' => 'asc', 'value' => 2023]) }}" class="dropdown-item">2023</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'periode', 'direction' => 'asc', 'value' => 2024]) }}" class="dropdown-item">2024</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'periode', 'direction' => 'asc', 'value' => 2025]) }}" class="dropdown-item">2025</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'periode', 'direction' => 'asc', 'value' => 2026]) }}" class="dropdown-item">2026</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'periode', 'direction' => 'asc', 'value' => 2027]) }}" class="dropdown-item">2027</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'periode', 'direction' => 'asc', 'value' => 2028]) }}" class="dropdown-item">2028</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'periode', 'direction' => 'asc', 'value' => 2029]) }}" class="dropdown-item">2029</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'periode', 'direction' => 'asc', 'value' => 2030]) }}" class="dropdown-item">2026</a></li>
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
                  <li><a href="{{ route('kota', ['sort' => 'kelas', 'direction' => 'asc', 'value' => 1]) }}" class="dropdown-item">D3-A</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'kelas', 'direction' => 'asc', 'value' => 2]) }}" class="dropdown-item">D3-B</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'kelas', 'direction' => 'asc', 'value' => 3]) }}" class="dropdown-item">D4-A</a></li>
                  <li><a href="{{ route('kota', ['sort' => 'kelas', 'direction' => 'asc', 'value' => 4]) }}" class="dropdown-item">D4-B</a></li>
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
  <div class="content">
    <!-- Begin Page Content -->
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
                <a href="{{ route('yudisium.kelola') }}" class="btn btn-app">
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
              <h3>{{ $totalYudisium1 ?? '12' }}</h3>
              <p>Yudisium 1</p>
            </div>
            <div class="icon">
              <i class="fas fa-trophy"></i>
            </div>
            <a href="{{ route('yudisium.kelola', ['kategori' => 1]) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $totalYudisium2 ?? '18' }}</h3>
              <p>Yudisium 2</p>
            </div>
            <div class="icon">
              <i class="fas fa-medal"></i>
            </div>
            <a href="{{ route('yudisium.kelola', ['kategori' => 2]) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $totalYudisium3 ?? '10' }}</h3>
              <p>Yudisium 3</p>
            </div>
            <div class="icon">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <a href="{{ route('yudisium.kelola', ['kategori' => 3]) }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title"><strong>Distribusi Kategori Yudisium</strong></h2>
            </div>
            <div class="card-body">
              <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title"><strong>Distribusi Status Yudisium</strong></h2>
            </div>
            <div class="card-body">
              <canvas id="pieChart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title"><strong>Distribusi Kelas</strong></h2>
            </div>
            <div class="card-body">
              <canvas id="pieChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><strong>Progres Seminar TA</strong></h3>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  


  <script>
    document.addEventListener('DOMContentLoaded', function () {

        //-------------
        //- PIE CHART - Distribusi Kategori Yudisium
        //-------------
        var pieChartCanvas = document.getElementById('pieChart').getContext('2d');
        var pieData = {
            labels: ['Yudisium 1', 'Yudisium 2', 'Yudisium 3'],
            datasets: [{
                label: 'Kategori Yudisium',
                data: [12, 18, 10], // Data statis, idealnya diambil dari database
                backgroundColor: [
                    'rgba(0, 165, 90, 0.8)',  // Hijau untuk Yudisium 1
                    'rgba(243, 156, 18, 0.8)', // Kuning untuk Yudisium 2
                    'rgba(221, 75, 57, 0.8)'   // Merah untuk Yudisium 3
                ],
                borderColor: [
                    'rgba(0, 165, 90, 1)',
                    'rgba(243, 156, 18, 1)',
                    'rgba(221, 75, 57, 1)'
                ],
                borderWidth: 1
            }]
        };
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        };

        // Create pie chart
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        });

        //-------------
        //- PIE CHART 1 - Status Yudisium
        //-------------
        var pieChartCanvas1 = document.getElementById('pieChart1').getContext('2d');
        var pieData1 = {
            labels: ['Pending', 'Approved', 'Rejected'],
            datasets: [{
                label: 'Status Yudisium',
                data: [15, 20, 5], // Data statis, idealnya diambil dari database
                backgroundColor: [
                    'rgba(243, 156, 18, 0.8)', // Kuning untuk Pending
                    'rgba(0, 165, 90, 0.8)',   // Hijau untuk Approved
                    'rgba(221, 75, 57, 0.8)'   // Merah untuk Rejected
                ],
                borderColor: [
                    'rgba(243, 156, 18, 1)',
                    'rgba(0, 165, 90, 1)',
                    'rgba(221, 75, 57, 1)'
                ],
                borderWidth: 1
            }]
        };
        var pieOptions1 = {
            maintainAspectRatio: false,
            responsive: true,
        };

        // Create pie chart
        new Chart(pieChartCanvas1, {
            type: 'pie',
            data: pieData1,
            options: pieOptions1
        });

        //-------------
        //- PIE CHART 2 - Distribusi Kelas
        //-------------
        var pieChartCanvas2 = document.getElementById('pieChart2').getContext('2d');
        var pieData2 = {
            labels: ['D3-A', 'D3-B', 'D4-A', 'D4-B'],
            datasets: [{
                label: 'Kelas',
                data: [10, 10, 10, 10], // Data statis, idealnya diambil dari database
                backgroundColor: [
                    'rgba(60, 141, 188, 0.8)',
                    'rgba(0, 192, 239, 0.8)',
                    'rgba(0, 166, 90, 0.8)',
                    'rgba(96, 92, 168, 0.8)'
                ],
                borderColor: [
                    'rgba(60, 141, 188, 1)',
                    'rgba(0, 192, 239, 1)',
                    'rgba(0, 166, 90, 1)',
                    'rgba(96, 92, 168, 1)'
                ],
                borderWidth: 1
            }]
        };
        var pieOptions2 = {
            maintainAspectRatio: false,
            responsive: true,
        };

        // Create pie chart
        new Chart(pieChartCanvas2, {
            type: 'pie',
            data: pieData2,
            options: pieOptions2
        });


        //-------------
        //- BAR CHART - Progres TA
        //-------------
        var barChartCanvas = document.getElementById('barChart').getContext('2d');
        var barChartData = {
            labels: ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'],
            datasets: [{
                label: 'Selesai',
                data: [40, 30, 20, 10], // Data statis, idealnya diambil dari database
                backgroundColor: 'rgba(60, 141, 188, 0.8)',
                borderColor: 'rgba(60, 141, 188, 1)',
                borderWidth: 1
            },
            {
                label: 'On Progress',
                data: [0, 10, 20, 30], // Data statis, idealnya diambil dari database
                backgroundColor: 'rgba(0, 166, 90, 0.8)',
                borderColor: 'rgba(0, 166, 90, 1)',
                borderWidth: 1
            }]
        };
        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
        };

        // Create bar chart
        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        });

      });
  </script>
  
@endsection