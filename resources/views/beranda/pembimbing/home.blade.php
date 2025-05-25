@extends('adminlte.layouts.app')

@section('content')

<div class="content-wrapper">
  <h3 class="mb-4">Dashboard Pembimbing TA</h3>
  <hr>

  <!-- Kartu Ringkasan -->
  <div class="row text-center">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-users fa-2x text-primary mb-2"></i>
          <h6>Total KoTA Bimbingan</h6>
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
      <div class="card cursor-pointer" onclick="showKotaUjiModal()" style="cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        <div class="card-body">
          <i class="fas fa-tasks fa-2x text-info mb-2"></i>
          <h6>Total KoTA yang Diuji</h6>
          <h3>{{ $totalKotaUji }}</h3>
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
      {{-- <pre>{{ var_dump($chartData) }}</pre> --}}
    </div>
  </div>

  <!-- List KoTA Bimbingan -->
  <div class="card mt-4">
    <div class="card-header">
      <strong>List KoTA Bimbingan</strong>
      <div class="float-right">
        <small class="text-muted">Total: {{ $totalKota }} KoTA</small>
      </div>
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
            <th>Aksi</th>
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
                @if(($status[0] ?? '-') === 'tuntas')
                  <span class="badge badge-success">Tuntas</span>
                @elseif(($status[0] ?? '-') === 'progress')
                  <span class="badge badge-warning">Progress</span>
                @else
                  <span class="badge badge-secondary">Belum</span>
                @endif
              </td>
              <td>
                @if(($status[1] ?? '-') === 'tuntas')
                  <span class="badge badge-success">Tuntas</span>
                @elseif(($status[1] ?? '-') === 'progress')
                  <span class="badge badge-warning">Progress</span>
                @else
                  <span class="badge badge-secondary">Belum</span>
                @endif
              </td>
              <td>
                @if(($status[2] ?? '-') === 'tuntas')
                  <span class="badge badge-success">Tuntas</span>
                @elseif(($status[2] ?? '-') === 'progress')
                  <span class="badge badge-warning">Progress</span>
                @else
                  <span class="badge badge-secondary">Belum</span>
                @endif
              </td>
              <td>
                @if(($status[3] ?? '-') === 'tuntas')
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
                @if($last && $last->status === 'tuntas' && $last->id_master_tahapan_progres == 4)
                  <span class="badge badge-success">Selesai</span>
                @else
                  <span class="badge badge-warning">Dalam Progres</span>
                @endif
              </td>
              <td>
                <div class="btn-group" role="group">
                  <button type="button" class="btn btn-sm btn-info" title="Lihat Artefak">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center">
                <div class="py-4">
                  <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                  <h5 class="text-muted">Belum Ada KoTA Bimbingan</h5>
                  <p class="text-muted">Anda belum memiliki KoTA yang dibimbing</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $kotaList->links() }}
        </div>
    </div>
  </div>
</div>

<!-- Modal KoTA yang Diuji -->
<div class="modal fade" id="kotaUjiModal" tabindex="-1" role="dialog" aria-labelledby="kotaUjiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="kotaUjiModalLabel">
          <i class="fas fa-tasks mr-2"></i>
          List KoTA yang Diuji
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="kotaUjiTable">
            <thead class="thead-light">
              <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Kelompok</th>
                <th width="35%">Judul</th>
                <th width="15%">Tahapan Terakhir</th>
                <th width="10%">Status</th>
              </tr>
            </thead>
            <tbody id="kotaUjiTableBody">
              <!-- Data akan dimuat via AJAX -->
            </tbody>
          </table>
        </div>
        
        <div class="d-flex justify-content-center mt-3">
          <nav aria-label="Page navigation">
            <ul class="pagination" id="kotaUjiPagination">
              <!-- Pagination akan dimuat via AJAX -->
            </ul>
          </nav>
        </div>
        
        <div class="text-center" id="loadingKotaUji" style="display: none;">
          <i class="fas fa-spinner fa-spin fa-2x text-info"></i>
          <p class="mt-2">Memuat data...</p>
        </div>
        
        <div class="text-center" id="emptyKotaUji" style="display: none;">
          <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">Tidak Ada KoTA yang Diuji</h5>
          <p class="text-muted">Anda belum memiliki KoTA yang diuji</p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
      name: 'Jumlah KoTA Tuntas',
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
        text: 'Jumlah KoTA'
      },
      labels: {
        formatter: function (val) {
          return Math.floor(val); // Pastikan Y-axis hanya menampilkan integer
        }
      },
      forceNiceScale: false,
      decimalsInFloat: 0, // Tidak ada desimal
      tickAmount: undefined, // Biarkan ApexCharts menentukan jumlah tick otomatis
      min: 0, // Mulai dari 0
      stepSize: 1 // Step dari 0 langsung ke 1, 2, 3, dst
    },
    colors: ['#28a745'], // Warna hijau untuk semua bar
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: false,
        dataLabels: {
          position: 'top'
        }
      }
    },
    dataLabels: {
      enabled: true,
      offsetY: -20,
      style: {
        fontSize: '12px',
        colors: ["#304758"]
      },
      formatter: function (val) {
        return Math.floor(val); // Tampilkan data label sebagai integer
      }
    },
    title: {
      text: 'Progress Tahapan KoTA Bimbingan',
      align: 'center'
    },
    grid: {
      show: true,
      borderColor: '#e7e7e7'
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return Math.floor(val) + " KoTA"; // Tooltip juga integer
        }
      }
    }
  };
  var chart = new ApexCharts(document.querySelector("#tahapanChart"), options);
  chart.render();
});

  // Fungsi untuk menampilkan modal
  function showKotaUjiModal() {
    $('#kotaUjiModal').modal('show');
    loadKotaUjiData(1); // Load halaman pertama
  }

  // Fungsi untuk memuat data KoTA yang diuji
  function loadKotaUjiData(page = 1, search = '') {
    $('#loadingKotaUji').show();
    $('#kotaUjiTableBody').hide();
    $('#emptyKotaUji').hide();
    $('#kotaUjiPagination').hide();

    // Simulasi AJAX call - ganti dengan endpoint yang sebenarnya
    $.ajax({
      url: '{{ route("dashboard.kota-uji") }}', // Buat route ini di controller
      method: 'GET',
      data: {
        page: page,
        search: search,
        per_page: 10
      },
      success: function(response) {
        $('#loadingKotaUji').hide();
        
        if (response.data && response.data.length > 0) {
          renderKotaUjiTable(response.data, response.pagination);
          renderKotaUjiPagination(response.pagination);
          $('#kotaUjiTableBody').show();
          $('#kotaUjiPagination').show();
        } else {
          $('#emptyKotaUji').show();
        }
      },
      error: function() {
        $('#loadingKotaUji').hide();
        alert('Terjadi kesalahan saat memuat data');
      }
    });
  }

    function renderKotaUjiTable(data, pagination) {
    let html = '';
    data.forEach((kota, index) => {
        const no = ((pagination.current_page - 1) * pagination.per_page) + index + 1;
        
        // Tentukan tahapan terakhir yang TUNTAS
        let tahapanTerakhir = 'Belum Dimulai';
        let statusBadge = 'secondary';
        
        if (kota.tahapan_progress && kota.tahapan_progress.length > 0) {
        const tahapanNames = ['', 'Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
        
        // Cari tahapan tertinggi yang statusnya TUNTAS
        let lastCompletedStage = 0;
        
        kota.tahapan_progress.forEach(progress => {
            if (progress.status === 'tuntas' && progress.id_master_tahapan_progres > lastCompletedStage) {
            lastCompletedStage = progress.id_master_tahapan_progres;
            }
        });
        
        // Jika ada tahapan yang tuntas, tampilkan tahapan terakhir yang tuntas
        if (lastCompletedStage > 0) {
            tahapanTerakhir = tahapanNames[lastCompletedStage] || 'Unknown';
            statusBadge = 'success'; // Tahapan yang sudah tuntas
        } else {
            // Jika tidak ada yang tuntas, cek apakah ada yang progress
            const hasProgress = kota.tahapan_progress.some(progress => progress.status === 'progress');
            if (hasProgress) {
            // Cari tahapan terendah yang sedang progress
            const progressStages = kota.tahapan_progress
                .filter(progress => progress.status === 'progress')
                .sort((a, b) => a.id_master_tahapan_progres - b.id_master_tahapan_progres);
            
            if (progressStages.length > 0) {
                tahapanTerakhir = tahapanNames[progressStages[0].id_master_tahapan_progres] || 'Unknown';
                statusBadge = 'warning'; // Sedang progress
            }
            }
        }
        }
        
        // Status keseluruhan
        let overallStatus = 'Dalam Progres';
        let overallBadge = 'warning';
        
        if (kota.tahapan_progress) {
        const sidangProgress = kota.tahapan_progress.find(tp => tp.id_master_tahapan_progres === 4);
        if (sidangProgress && sidangProgress.status === 'tuntas') {
            overallStatus = 'Selesai';
            overallBadge = 'success';
        }
        }

        html += `
        <tr>
            <td class="text-center">${no}</td>
            <td><strong>${kota.nama_kota}</strong></td>
            <td><small class="text-muted">${kota.judul || '-'}</small></td>
            <td>
            <span class="badge badge-${statusBadge}">${tahapanTerakhir}</span>
            </td>
            <td>
            <span class="badge badge-${overallBadge}">${overallStatus}</span>
            </td>
        </tr>
        `;
    });
    
    $('#kotaUjiTableBody').html(html);
    }

  function renderKotaUjiPagination(pagination) {
    let html = '';
    
    // Previous button
    if (pagination.current_page > 1) {
      html += `<li class="page-item">
        <a class="page-link" href="#" onclick="loadKotaUjiData(${pagination.current_page - 1}, $('#searchKotaUji').val())">
          <i class="fas fa-chevron-left"></i>
        </a>
      </li>`;
    }
    
    // Page numbers
    for (let i = 1; i <= pagination.last_page; i++) {
      if (i === pagination.current_page) {
        html += `<li class="page-item active">
          <span class="page-link">${i}</span>
        </li>`;
      } else {
        html += `<li class="page-item">
          <a class="page-link" href="#" onclick="loadKotaUjiData(${i}, $('#searchKotaUji').val())">${i}</a>
        </li>`;
      }
    }
    
    // Next button
    if (pagination.current_page < pagination.last_page) {
      html += `<li class="page-item">
        <a class="page-link" href="#" onclick="loadKotaUjiData(${pagination.current_page + 1}, $('#searchKotaUji').val())">
          <i class="fas fa-chevron-right"></i>
        </a>
      </li>`;
    }
    
    $('#kotaUjiPagination').html(html);
  }
</script>
@endpush