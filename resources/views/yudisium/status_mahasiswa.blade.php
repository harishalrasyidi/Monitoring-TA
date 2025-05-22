@extends('adminlte.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col">
          <h1 class="m-0">Status Yudisium</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
      <hr>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      @if(!$yudisium)
      <div class="alert alert-info">
        <h5><i class="icon fas fa-info"></i> Informasi</h5>
        Status yudisium belum tersedia. Silahkan hubungi koordinator TA untuk informasi lebih lanjut.
      </div>
      @else
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Informasi KoTA</h3>
            </div>
            <div class="card-body">
              <table class="table">
                <tr>
                  <th style="width: 200px">KoTA</th>
                  <td>{{ $kota->nama_kota }}</td>
                </tr>
                <tr>
                  <th>Judul</th>
                  <td>{{ $kota->judul }}</td>
                </tr>
                <tr>
                  <th>Kelas</th>
                  <td>
                    @if($kota->kelas == '1') D3-A
                    @elseif($kota->kelas == '2') D3-B
                    @elseif($kota->kelas == '3') D4-A
                    @elseif($kota->kelas == '4') D4-B
                    @else {{ $kota->kelas }}
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Periode</th>
                  <td>{{ $kota->periode }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Status Yudisium</h3>
            </div>
            <div class="card-body">
              <div class="text-center mb-4">
                <h4>Kategori Yudisium</h4>
                <div class="h1 mb-1 font-weight-bold">
                  @if($yudisium->kategori_yudisium == 1)
                  <span class="text-success">YUDISIUM 1</span>
                  @elseif($yudisium->kategori_yudisium == 2)
                  <span class="text-warning">YUDISIUM 2</span>
                  @else
                  <span class="text-danger">YUDISIUM 3</span>
                  @endif
                </div>
                <p class="text-muted">Tanggal Yudisium: {{ \Carbon\Carbon::parse($yudisium->tanggal_yudisium)->format('d F Y') }}</p>
                
                <div class="mt-4">
                  <h5>Status</h5>
                  @if($yudisium->status == 'pending')
                  <span class="badge badge-warning p-2" style="font-size: 1rem;">PENDING</span>
                  @elseif($yudisium->status == 'approved')
                  <span class="badge badge-success p-2" style="font-size: 1rem;">APPROVED</span>
                  @elseif($yudisium->status == 'rejected')
                  <span class="badge badge-danger p-2" style="font-size: 1rem;">REJECTED</span>
                  @endif
                </div>
                
                @if($yudisium->nilai_akhir)
                <div class="mt-4">
                  <h5>Nilai Akhir</h5>
                  <div class="h3 mb-0 font-weight-bold">{{ $yudisium->nilai_akhir }}</div>
                </div>
                @endif
                
                @if($yudisium->keterangan)
                <div class="mt-4">
                  <h5>Keterangan</h5>
                  <p>{{ $yudisium->keterangan }}</p>
                </div>
                @endif
              </div>
              
              <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Informasi</h5>
                Jika ada pertanyaan tentang status yudisium, silahkan hubungi koordinator TA.
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </section>
</div>
@endsection