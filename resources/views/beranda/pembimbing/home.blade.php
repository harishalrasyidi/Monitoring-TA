@extends('adminlte.layouts.app')

@section('content')

<div class="content-wrapper">
  <h3 class="mb-4">Dashboard Pembimbing</h3>
  <hr>

  <!-- Kartu Ringkasan -->
  <div class="row text-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-users fa-2x text-primary mb-2"></i>
          <h6>Total KoTA Bimbingan</h6>
          <h3>{{ $totalKota }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
          <h6>Selesai Semua Tahapan</h6>
          <h3>{{ $selesai }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-spinner fa-2x text-warning mb-2"></i>
          <h6>Dalam Progres</h6>
          <h3>{{ $dalamProgres }}</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik Tahapan KoTA -->
  <div class="card mt-4">
    <div class="card-header">
      <strong>Statistik Status Tahapan KoTA Bimbingan</strong>
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

  <!-- List KoTA Bimbingan -->
  <div class="card mt-4">
    <div class="card-header">
      <strong>List KoTA Bimbingan</strong>
    </div>
    <div class="card-body table-responsive">
      @if($kotaList->count() > 0)
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
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kotaList as $no => $kota)
              <tr>
                <td>{{ $no+1 }}</td>
                <td>
                  <strong>{{ $kota->nama_kota }}</strong><br>
                  <small class="text-muted">{{ $kota->judul }}</small>
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
                <td>
                  @if($status[0] === 'tuntas')
                    <span class="badge badge-success">{{ $status[0] }}</span>
                  @elseif($status[0] === 'belum')
                    <span class="badge badge-danger">{{ $status[0] }}</span>
                  @else
                    <span class="badge badge-secondary">{{ $status[0] }}</span>
                  @endif
                </td>
                <td>
                  @if($status[1] === 'tuntas')
                    <span class="badge badge-success">{{ $status[1] }}</span>
                  @elseif($status[1] === 'belum')
                    <span class="badge badge-danger">{{ $status[1] }}</span>
                  @else
                    <span class="badge badge-secondary">{{ $status[1] }}</span>
                  @endif
                </td>
                <td>
                  @if($status[2] === 'tuntas')
                    <span class="badge badge-success">{{ $status[2] }}</span>
                  @elseif($status[2] === 'belum')
                    <span class="badge badge-danger">{{ $status[2] }}</span>
                  @else
                    <span class="badge badge-secondary">{{ $status[2] }}</span>
                  @endif
                </td>
                <td>
                  @if($status[3] === 'tuntas')
                    <span class="badge badge-success">{{ $status[3] }}</span>
                  @elseif($status[3] === 'belum')
                    <span class="badge badge-danger">{{ $status[3] }}</span>
                  @else
                    <span class="badge badge-secondary">{{ $status[3] }}</span>
                  @endif
                </td>
                <td>
                  @php
                    $last = $kota->tahapanProgress->sortByDesc('id_master_tahapan_progres')->first();
                  @endphp
                  @if($last && $last->status === 'tuntas' && optional($last->masterTahapan)->nama_progres === 'Sidang')
                    <span class="badge badge-success">Selesai</span>
                  @else
                    <span class="badge badge-warning">Dalam Progres</span>
                  @endif
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info" title="Lihat Detail">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" title="Bimbingan">
                      <i class="fas fa-chalkboard-teacher"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center">Data tidak ditemukan</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @else
        <div class="alert alert-info text-center">
          <i class="fas fa-info-circle"></i>
          <h5>Belum Ada KoTA Bimbingan</h5>
          <p>Anda belum memiliki kelompok TA untuk dibimbing. Silakan hubungi koordinator untuk penugasan bimbingan.</p>
        </div>
      @endif
    </div>
  </div>


</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.41.0/apexcharts.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var options = {
      chart: { 
        type: 'bar', 
        height: 350,
        toolbar: {
          show: true
        }
      },
      series: [{
        name: 'Jumlah Mahasiswa',
        data: {!! json_encode(array_values($chartData)) !!}
      }],
      xaxis: {
        categories: {!! json_encode(array_keys($chartData)) !!},
        title: {
          text: 'Tahapan'
        }
      },
      yaxis: {
        title: {
          text: 'Jumlah Kelompok'
        }
      },
      colors: ['#28a745', '#ffc107', '#fd7e14', '#007bff'],
      plotOptions: {
        bar: {
          borderRadius: 4,
          horizontal: false,
        }
      },
      dataLabels: {
        enabled: true
      },
      title: {
        text: 'Progress Tahapan KoTA Bimbingan',
        align: 'center'
      }
    };
    
    var chart = new ApexCharts(document.querySelector("#tahapanChart"), options);
    chart.render();
  });
</script>
@endsection