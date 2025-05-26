@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
  <h3 class="mb-4">Detail Artefak</h3>
  <hr>

  @php
    $tahapanData = [
      'Seminar 1' => $seminar1,
      'Seminar 2' => $seminar2,
      'Seminar 3' => $seminar3,
      'Sidang' => $sidang
    ];
  @endphp
        
  @foreach ($tahapanData as $namaTahapan => $dataArtefak)
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
              <th>File Pengumpulan</th>
              <th>Waktu Pengumpulan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($dataArtefak as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_artefak ?? '-' }}</td>
                <td>
                  @if($item->file_pengumpulan)
                    <a href="{{ asset('storage/' . $item->file_pengumpulan) }}" target="_blank" class="btn btn-sm btn-primary">Lihat File</a>
                  @else
                    Belum Ada
                  @endif
                </td>
                <td>{{ $item->waktu_pengumpulan ?? '-' }}</td>
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