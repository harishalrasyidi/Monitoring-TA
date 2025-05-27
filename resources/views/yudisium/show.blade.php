@extends('adminlte.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col">
          <h1 class="m-0">Detail Yudisium</h1>
        </div><!-- /.col -->
        <div class="col d-flex justify-content-end">
          <div class="btn-group">
            @if(in_array(Auth::user()->role, [1, 4, 5]))
            <a href="{{ route('yudisium.edit', $yudisium->id_yudisium) }}" class="btn btn-warning">Edit</a>
            @endif
            <a href="{{ route('yudisium.index') }}" class="btn btn-secondary">Kembali</a>
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
      <div class="row">
        <!-- Detail Yudisium -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Informasi Yudisium</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 200px">KoTA</th>
                  <td>{{ $yudisium->nama_kota }}</td>
                </tr>
                <tr>
                  <th>Judul TA</th>
                  <td>{{ $yudisium->judul }}</td>
                </tr>
                <tr>
                  <th>Kelas</th>
                  <td>
                    @if($yudisium->kelas == '1') D3-A
                    @elseif($yudisium->kelas == '2') D3-B
                    @elseif($yudisium->kelas == '3') D4-A
                    @elseif($yudisium->kelas == '4') D4-B
                    @else {{ $yudisium->kelas }}
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Periode</th>
                  <td>{{ $yudisium->periode }}</td>
                </tr>
                <tr>
                  <th>Mahasiswa</th>
                  <td>{{ $yudisium->mahasiswa }}</td>
                </tr>
                <tr>
                  <th>Dosen Pembimbing</th>
                  <td>{{ $yudisium->dosen }}</td>
                </tr>
                <tr>
                  <th>Kategori Yudisium</th>
                  <td>Yudisium {{ $yudisium->kategori_yudisium }}</td>
                </tr>
                <tr>
                  <th>Tanggal Yudisium</th>
                  <td>{{ \Carbon\Carbon::parse($yudisium->tanggal_yudisium)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                  <th>Nilai Akhir</th>
                  <td>{{ $yudisium->nilai_akhir }}</td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td>
                    @if($yudisium->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                    @elseif($yudisium->status == 'approved')
                    <span class="badge badge-success">Approved</span>
                    @elseif($yudisium->status == 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Keterangan</th>
                  <td>{{ $yudisium->keterangan ?? '-' }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- History Log -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Riwayat Perubahan</h3>
            </div>
            <div class="card-body">
              <div class="timeline">
                @forelse($logs as $log)
                <div>
                  <i class="fas fa-clock bg-blue"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($log->waktu_perubahan)->format('d-m-Y H:i') }}</span>
                    <h3 class="timeline-header">{{ $log->jenis_perubahan }} oleh {{ $log->nama_user }}</h3>
                    <div class="timeline-body">
                      {{ $log->keterangan }}
                    </div>
                  </div>
                </div>
                @empty
                <div>
                  <i class="fas fa-info bg-gray"></i>
                  <div class="timeline-item">
                    <h3 class="timeline-header">Tidak ada riwayat perubahan</h3>
                  </div>
                </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection