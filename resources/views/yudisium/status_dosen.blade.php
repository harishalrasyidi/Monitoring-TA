@extends('adminlte.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col">
          <h1 class="m-0">Status Yudisium Mahasiswa</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
      <hr>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Mahasiswa Bimbingan</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NIM</th>
                  <th>Nama Mahasiswa</th>
                  <th>KoTA</th>
                  <th>Judul</th>
                  <th>Kategori Yudisium</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($mahasiswaYudisium as $index => $item)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $item->nim }}</td>
                  <td>{{ $item->nama_mahasiswa }}</td>
                  <td>{{ $item->nama_kota }}</td>
                  <td>{{ Str::limit($item->judul, 50) }}</td>
                  <td>
                    @if($item->kategori_yudisium)
                    Yudisium {{ $item->kategori_yudisium }}
                    @else
                    <span class="text-muted">Belum ada</span>
                    @endif
                  </td>
                  <td>
                    @if(!$item->status)
                    <span class="badge badge-secondary">Belum ada</span>
                    @elseif($item->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                    @elseif($item->status == 'approved')
                    <span class="badge badge-success">Approved</span>
                    @elseif($item->status == 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                    @endif
                  </td>
                  <td>
                    @if($item->id_yudisium)
                    <a href="{{ route('yudisium.show', $item->id_yudisium) }}" class="btn btn-sm btn-info">Detail</a>
                    @else
                    <button class="btn btn-sm btn-secondary" disabled>Tidak tersedia</button>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center">Tidak ada data mahasiswa bimbingan.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection