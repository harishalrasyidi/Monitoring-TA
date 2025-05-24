@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
  <h3 class="mb-4">Detail Artefak - {{ $kota->nama_kota }}</h3>
  <hr>

  <div class="card">
    <div class="card-header">
      <strong>Artefak yang Dikumpulkan</strong>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th>No</th>
            <th>Nama Artefak</th>
            <th>Deskripsi</th>
            <th>Tenggat Waktu</th>
            <th>File Pengumpulan</th>
            <th>Waktu Pengumpulan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kota->artefakRelasi as $index => $relasi)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $relasi->artefak->nama_artefak ?? '-' }}</td>
              <td>{{ $relasi->artefak->deskripsi ?? '-' }}</td>
              <td>{{ $relasi->artefak->tenggat_waktu ?? '-' }}</td>
              <td>
                @if($relasi->file_pengumpulan)
                  <a href="{{ asset('storage/artefak/'.$relasi->file_pengumpulan) }}" target="_blank">Download</a>
                @else
                  Belum Ada
                @endif
              </td>
              <td>{{ $relasi->waktu_pengumpulan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">Belum ada artefak dikumpulkan</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
