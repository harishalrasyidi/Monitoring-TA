@extends('adminlte.layouts.app')

@section('content')

  <!-- Content Wrapper. Berisi konten halaman -->
  <div class="content-wrapper">
    <!-- Header Konten (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h1 class="m-0">Resume Bimbingan</h1>
            </div><!-- /.col -->
            <div class="col d-flex justify-content-end">
                <div class="btn-group mr-2">
                    <!-- Menu Dropdown Seminar dan Sidang -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="seminarsDropdown" data-toggle="dropdown" aria-expanded="false">
                            Tahapan TA
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="seminarsDropdown">
                            <li><a href="{{ route('resume', ['sort' => 'tahapan_progres', 'direction' => 'asc', 'value' => 2]) }}" class="dropdown-item">Seminar 2</a></li>
                            <li><a href="{{ route('resume', ['sort' => 'tahapan_progres', 'direction' => 'asc', 'value' => 3]) }}" class="dropdown-item">Seminar 3</a></li>
                            <li><a href="{{ route('resume', ['sort' => 'tahapan_progres', 'direction' => 'asc', 'value' => 4]) }}" class="dropdown-item">Sidang</a></li>
                        </ul>
                    </div>
                </div>
                @if (auth()->user()->role == "3")
                <div class="btn-group">
                    <a href="{{ route('resume.create') }}">
                        <button type="button" class="btn btn-success">
                            Tambah
                            <i class="nav-icon fas fa-plus"></i>
                        </button>
                    </a>
                </div>
                @endif
            </div><!-- /.col -->
        </div><!-- /.row -->
        <hr/>
    </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Konten utama -->
    <div class="content">
      <!-- Mulai Konten Halaman -->
      <div class="container-fluid">
        <!-- Contoh DataTables -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="row mt-4">
              @foreach($resumes as $resume)
              <div class="col-md-4">
                <div class="card">
                  <h5 class="card-header d-flex justify-content-between align-items-center">
                    <div class="col">Resume Bimbingan Ke-{{ $resume->sesi_bimbingan }}</div>
                    @if (auth()->user()->role == "3")
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                      <div class="btn-group mr-2">
                        <a href="{{ route('resume.edit', $resume->id_resume_bimbingan) }}"  data-toggle="tooltip" data-placement="top" title="Edit Resume Bimbingan">
                          <i class="nav-icon fas fa-pen" style="color: blue;"></i>
                        </a>
                      </div>
                      <div class="btn-group">
                        <a href="{{ route('resume.generatePdf', ['sesi_bimbingan' => $resume->id_resume_bimbingan]) }}" data-toggle="tooltip" data-placement="top" title="Download PDF">
                          <i class="nav-icon fas fa-file-pdf" style="color: red;"></i>
                        </a>
                      </div>
                    </div>
                    @endif
                  </h5>
                  <div class="card-body">
                    <div class="row">
                      <small class="col">{{ $resume->tanggal_bimbingan }}</small>
                      <p class="col"><small class="text-muted">{{ $resume->jam_mulai }} - {{ $resume->jam_selesai }}</small></p>
                    </div>
                    <div class="row">
                      <div class="col">
                        @php
                          $maxLength = 100;
                          $content = $resume->isi_resume_bimbingan;
                          $shortContent = strlen($content) > $maxLength ? substr($content, 0, $maxLength) . '...' : $content;
                        @endphp
                        <h5>Resume</h5>
                        <small>
                          {{ $shortContent }}
                          @if(strlen($content) > $maxLength)
                            <a href="{{ route('resume.detail', ['id' => $resume->id_resume_bimbingan]) }}">Baca Selengkapnya</a>
                          @endif
                        </small>
                      </div>
                      <div class="col">
                        @php
                          $maxLength = 100;
                          $content = $resume->isi_revisi_bimbingan;
                          $shortContent = strlen($content) > $maxLength ? substr($content, 0, $maxLength) . '...' : $content;
                        @endphp
                        <h5>Revisi</h5>
                        <small>
                          @if($content != '-')
                            {{ $shortContent }}
                            @if(strlen($content) > $maxLength)
                              <a href="{{ route('resume.detail', ['id' => $resume->id_resume_bimbingan]) }}">Baca Selengkapnya</a>
                            @endif
                          @else
                            Belum Direvisi
                          @endif
                        </small>
                      </div>
                    </div>
                  </div>
                  <!-- Ikon tanda ceklis hanya muncul jika ada revisi bimbingan, dan bukan default '-' -->
                  <div class="card-footer">
                    @if($resume->isi_revisi_bimbingan != '-' && $resume->isi_revisi_bimbingan != '')
                      <i class="fas fa-check-circle text-success"></i> Telah Direvisi
                    @endif
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
