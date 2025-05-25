@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
  <h3 class="mb-4">Detail Artefak - {{ $kota->nama_kota }}</h3>
  <hr>

  @php
    $tahapanList = [
      1 => 'Seminar 1',
      2 => 'Seminar 2',
      3 => 'Seminar 3',
      4 => 'Sidang'
    ];
  @endphp
        
  @foreach ($tahapanList as $idTahapan => $namaTahapan)
    @php
        $filtered = $kota->artefakRelasi->filter(function($item) use ($idTahapan) {
            return $item->artefak?->master?->id == $idTahapan;
        });
    @endphp

    <div class="card mb-4">
      <div class="card-header bg-light">
        <strong>{{ $namaTahapan }}</strong>
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
            @forelse($filtered as $index => $relasi)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $relasi->artefak->nama_artefak ?? '-' }}</td>
                <td>{{ $relasi->artefak->deskripsi ?? '-' }}</td>
                <td>{{ $relasi->artefak->tenggat_waktu ?? '-' }}</td>
                <td>
                  @if($relasi->file_pengumpulan)
                    <a href="{{ route('file.show', ['nama_artefak' => $relasi->artefak->nama_artefak]) }}" target="_blank">Lihat File</a>
                  @else
                    Belum Ada
                  @endif
                </td>
                <td>{{ $relasi->waktu_pengumpulan ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Belum ada artefak untuk {{ $namaTahapan }}</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  @endforeach
</div>
@endsection
