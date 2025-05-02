@extends('adminlte.layouts.app')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6 col-md-8">
                  <h1 class="m-0">Tambah KoTA</h1>
              </div><!-- /.col -->
          </div><!-- /.row -->
        <hr/>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <!-- Begin Page Content -->
      <div class="container-fluid">
        <form action="{{ url('/kota/store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row card-group-row">
            <div class="col-md-12">
                @if (session('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif
                    
                    <!-- Nomor KoTA -->
                    <div class="list-group-item p-3">
                        <div class="row align-items-start">
                            <div class="col-md-2 mb-8pt mb-md-0">
                                <div class="media align-items-left">
                                    <div class="d-flex flex-column media-body media-middle">
                                        <span class="card-title">Nomor KoTA</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0">
                                <input name="nama_kota" value="{{ old('nama_kota') }}" type="text" class="form-control" placeholder="Masukan Id KoTA" required/>
                            </div>
                        </div>
                    </div>

                    <!-- Tambah Mahasiswa dan Dosen Pembimbing -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-4 mb-8pt mb-md-0">
                                        <div class="media align-items-left">
                                            <div class="d-flex flex-column media-body media-middle">
                                                <span class="card-title" for="mahasiswa">Mahasiswa 1</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" id="mahasiswa" name="mahasiswa[]" required>
                                            <option value="" disabled selected>Pilih Mahasiswa</option>
                                            @foreach($mahasiswa as $m)
                                                <option value="{{ $m->nomor_induk }}" {{ in_array($m->nomor_induk, old('mahasiswa', [])) ? 'selected' : '' }}>{{ $m->nomor_induk }} - {{ $m->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-4 mb-8pt mb-md-0">
                                        <div class="media align-items-left">
                                            <div class="d-flex flex-column media-body media-middle">
                                                <span class="card-title" for="mahasiswa">Mahasiswa 2</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" id="mahasiswa" name="mahasiswa[]" required>
                                            <option value="" disabled selected>Pilih Mahasiswa</option>
                                            @foreach($mahasiswa as $m)
                                                <option value="{{ $m->nomor_induk }}" {{ in_array($m->nomor_induk, old('mahasiswa', [])) ? 'selected' : '' }}>{{ $m->nomor_induk }} - {{ $m->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-4 mb-8pt mb-md-0">
                                        <div class="media align-items-left">
                                            <div class="d-flex flex-column media-body media-middle">
                                                <span class="card-title" for="mahasiswa">Mahasiswa 3</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" id="mahasiswa" name="mahasiswa[]" required>
                                            <option value="" disabled selected>Pilih Mahasiswa</option>
                                            @foreach($mahasiswa as $m)
                                                <option value="{{ $m->nomor_induk }}" {{ in_array($m->nomor_induk, old('mahasiswa', [])) ? 'selected' : '' }}>{{ $m->nomor_induk }} - {{ $m->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-md-6">
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-4 mb-8pt mb-md-0">
                                        <div class="media align-items-left">
                                            <div class="d-flex flex-column media-body media-middle">
                                                <span class="card-title" for="dosen">Dosen Pembimbing</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <select multiple class="form-control" id="dosen" name="dosen[]" required>
                                            @foreach($dosen as $d)
                                                <option value="{{ $d->nomor_induk }}" {{ in_array($d->nomor_induk, old('dosen', [])) ? 'selected' : '' }}>{{ $d->nomor_induk }} - {{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <!-- <div class="row">
                        <div class="col">
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-2 mb-8pt mb-md-0">
                                        <div class="media align-items-left">
                                            <div class="d-flex flex-column media-body media-middle">
                                                <span class="card-title" for="mahasiswa_nomor_induk">NIM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mb-8pt mb-md-0">
                                        <select class="form-select" id="mahasiswa_nomor_induk" name="mahasiswa_nomor_induk[]" required>
                                            <option value="" disabled selected>Pilih Nomor Induk</option>
                                            @foreach($mahasiswa as $m)
                                                <option value="{{ $m->nomor_induk }}" data-name="{{ $m->name }}">{{ $m->nomor_induk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-2 mb-8pt mb-md-0">
                                        <div class="media align-items-left">
                                            <div class="d-flex flex-column media-body media-middle">
                                                <span class="card-title" for="mahasiswa_name">Anggota</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mb-8pt mb-md-0">
                                        <select class="form-select" id="mahasiswa_name" name="mahasiswa_name[]">
                                            <option value="" disabled selected>Pilih Nama</option>
                                            @foreach($mahasiswa as $m)
                                                <option value="{{ $m->id }}" data-nomor_induk="{{ $m->nomor_induk }}">{{ $m->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-2 mb-8pt mb-md-0">
                                        <div class="media align-items-left">
                                            <div class="d-flex flex-column media-body media-middle">
                                                <span class="card-title" for="dosen_nomor_induk" >NIP</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mb-8pt mb-md-0">
                                        <select class="form-select" id="dosen_nomor_induk" name="dosen_nomor_induk[]" required>
                                            <option value="" disabled selected>Pilih Nomor Induk</option>
                                            @foreach($dosen as $d)
                                                <option value="{{ $d->nomor_induk }}" data-name="{{ $d->name }}">{{ $d->nomor_induk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-2 mb-8pt mb-md-0">
                                        <div class="media align-items-left">
                                            <div class="d-flex flex-column media-body media-middle">
                                                <span class="card-title" for="dosen_name">Dosen</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mb-8pt mb-md-0">
                                        <select ct class="form-select" id="dosen_name" name="dosen_name[]">
                                            <option value="" disabled selected>Pilih Nama</option>
                                            @foreach($dosen as $d)
                                                <option value="{{ $d->id }}" data-nomor_induk="{{ $d->nomor_induk }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- JUDUL KUTIPAN -->
                    <div class="list-group-item p-3">
                        <div class="row align-items-start">
                            <div class="col-md-2 mb-8pt mb-md-0">
                                <div class="media align-items-left">
                                    <div class="d-flex flex-column media-body media-middle">
                                        <span class="card-title">Judul</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0">
                                <input name="judul" value="{{ old('judul') }}" type="text" class="form-control" placeholder="Masukan Judul" required/>
                            </div>
                        </div>
                    </div>

                    <!-- Periode -->
                    <div class="list-group-item p-3">
                        <div class="row align-items-start">
                            <div class="col-md-2 mb-8pt mb-md-0">
                                <div class="media align-items-left">
                                    <div class="d-flex flex-column media-body media-middle">
                                        <span class="card-title">Periode</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0">
                                <input name="periode" value="{{ old('periode') }}" type="text" class="form-control" placeholder="Masukan Periode Tahun" required/>
                            </div>
                        </div>
                    </div>

                    <!-- KELAS -->
                    <div class="list-group-item p-3">
                        <div class="row align-items-start">
                            <div class="col-md-2 mb-8pt mb-md-0">
                                <div class="media align-items-left">
                                    <div class="d-flex flex-column media-body media-middle">
                                        <span class="card-title" for="kelas">Kelas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0">
                                <select name="kelas" class="form-control" id="kelas" required>
                                    <option value="" disabled selected>Pilih Kelas</option>
                                    <option value="1" {{ old('kelas') == 1 ? 'selected' : '' }}>D3-A</option>
                                    <option value="2" {{ old('kelas') == 2 ? 'selected' : '' }}>D3-B</option>
                                    <option value="3" {{ old('kelas') == 3 ? 'selected' : '' }}>D4-A</option>
                                    <option value="4" {{ old('kelas') == 4 ? 'selected' : '' }}>D4-B</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!-- TAHAPAN PROGRES -->
                    <!-- <div class="list-group-item p-3">
                        <div class="row align-items-start">
                            <div class="col-md-2 mb-8pt mb-md-0">
                                <div class="media align-items-left">
                                    <div class="d-flex flex-column media-body media-middle">
                                        <span class="card-title">Tahapan Progres</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0">
                                <input name="tahapan_progres" value="{{ old('tahapan_progres') }}" type="text" class="form-control" placeholder="Masukan Tahapan Progres" required/>
                            </div>
                        </div>
                    </div> -->
                    
                </div>
            </div>
            <br>
              <div class="row">
                <div class="col-auto me-auto">
                </div>
                <div class="col-auto" style="margin-left: auto;">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
        </div>
        </form>
        <br/>
        <br/>
        <br/>
      </div>
    </div>
  </div>
<!-- End of Main Content -->
@endsection
