@extends('adminlte.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col">
          <h1 class="m-0">Kelola Yudisium</h1>
        </div><!-- /.col -->
        <div class="col d-flex justify-content-end">
          <div class="btn-group mr-2">
            <a href="{{ route('yudisium.create') }}" class="btn btn-primary">Tambah Data</a>
          </div>
          <div class="btn-group mr-2">
            <a href="{{ route('yudisium.export') }}" class="btn btn-success">Export Excel</a>
          </div>
          <div class="btn-group mr-2">
            <a href="{{ route('yudisium.dashboard') }}" class="btn btn-info">Dashboard</a>
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
          <form method="GET" action="{{ route('yudisium.index') }}" class="row">
            <div class="col-md-3 form-group">
              <label>Periode</label>
              <select name="periode" class="form-control">
                <option value="">Semua Periode</option>
                @foreach($periodeList as $p)
                <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 form-group">
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
            <div class="col-md-3 form-group">
              <label>Kategori Yudisium</label>
              <select name="kategori" class="form-control">
                <option value="">Semua Kategori</option>
                <option value="1" {{ $kategori == '1' ? 'selected' : '' }}>Yudisium 1</option>
                <option value="2" {{ $kategori == '2' ? 'selected' : '' }}>Yudisium 2</option>
                <option value="3" {{ $kategori == '3' ? 'selected' : '' }}>Yudisium 3</option>
              </select>
            </div>
            <div class="col-md-3 form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
              </select>
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="{{ route('yudisium.index') }}" class="btn btn-default">Reset</a>
            </div>
          </form>
        </div>
      </div>

      <!-- Chart Section -->
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Distribusi Kategori Yudisium</h3>
            </div>
            <div class="card-body">
              <canvas id="distribusiChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Table -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data Yudisium</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>KoTA</th>
                  <th>Judul</th>
                  <th>Mahasiswa</th>
                  <th>Dosen Pembimbing</th>
                  <th>Kategori Yudisium</th>
                  <th>Tanggal Yudisium</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($yudisium as $index => $item)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $item->nama_kota }}</td>
                  <td>{{ Str::limit($item->judul, 50) }}</td>
                  <td>{{ $item->mahasiswa }}</td>
                  <td>{{ $item->dosen }}</td>
                  <td>Yudisium {{ $item->kategori_yudisium }}</td>
                  <td>{{ \Carbon\Carbon::parse($item->tanggal_yudisium)->format('d-m-Y') }}</td>
                  <td>
                    @if($item->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                    @elseif($item->status == 'approved')
                    <span class="badge badge-success">Approved</span>
                    @elseif($item->status == 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group">
                      <a href="{{ route('yudisium.show', $item->id_yudisium) }}" class="btn btn-sm btn-info" title="Detail">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('yudisium.edit', $item->id_yudisium) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('yudisium.destroy', $item->id_yudisium) }}" method="POST" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="9" class="text-center">Tidak ada data yudisium.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="mt-4">
            {{ $yudisium->appends(request()->query())->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@push('scripts')
<script>
  $(function() {
    // Distribusi Chart
    var distribusiData = {
      labels: [
        @foreach($distribusi as $d)
        'Yudisium {{ $d->kategori_yudisium }}',
        @endforeach
      ],
      datasets: [{
        data: [
          @foreach($distribusi as $d)
          {{ $d->jumlah }},
          @endforeach
        ],
        backgroundColor: ['#f56954', '#00a65a', '#f39c12']
      }]
    };
    
    var distribusiCanvas = $('#distribusiChart').get(0).getContext('2d');
    new Chart(distribusiCanvas, {
      type: 'pie',
      data: distribusiData,
      options: {
        maintainAspectRatio: false,
        responsive: true,
      }
    });
  });
</script>
@endpush

@endsection