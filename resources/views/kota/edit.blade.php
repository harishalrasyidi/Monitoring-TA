@extends('adminlte.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 col-md-8">
                    <h1 class="m-0">Edit KoTA {{ ($kota->nama_kota) }}</h1>
                </div>
            </div>
            <hr />
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <form action="{{ route('kota.update', $kota->id_kota) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row card-group-row">
                    @if($errors->any())
                    <div class="col-md-12">
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-warning" role="alert">
                            <i class="mdi mdi-alert-outline me-2"></i>
                            {{ $error }}
                        </div>
                        @endforeach
                    </div>             
                    @endif
                    <div class="col-md-12">
                        <!-- Nama KoTA -->
                        <div class="list-group-item p-3">
                            <div class="row align-items-start">
                                <div class="col-md-2 mb-8pt mb-md-0">
                                    <div class="media align-items-left">
                                        <div class="d-flex flex-column media-body media-middle">
                                            <span class="card-title">Nama KoTA</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-8pt mb-md-0">
                                    <input name="nama_kota" value="{{ old('nama_kota', $kota->nama_kota) }}" type="text"
                                        class="form-control" required disabled />
                                </div>
                            </div>
                        </div>
                        <!-- Dosen Pembimbing -->
<div class="list-group-item p-3">
    <div class="row align-items-start">
        <div class="col-md-2 mb-8pt mb-md-0">
            <div class="media align-items-left">
                <div class="d-flex flex-column media-body media-middle">
                    <span class="card-title" for="dosen">Dosen Pembimbing</span>
                </div>
            </div>
        </div>
        <div class="col mb-8pt mb-md-0">
            <select multiple class="form-control" id="dosen" name="dosen[]" required>
                @foreach($dosen as $d)
                    <option value="{{ $d->id }}" {{ in_array($d->id, $selectedDosen) ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<!-- Mahasiswa -->
<div class="list-group-item p-3">
    <div class="row align-items-start">
        <div class="col-md-2 mb-8pt mb-md-0">
            <div class="media align-items-left">
                <div class="d-flex flex-column media-body media-middle">
                    <span class="card-title" for="mahasiswa">Mahasiswa</span>
                </div>
            </div>
        </div>
        <div class="col mb-8pt mb-md-0">
            <select multiple class="form-control" id="mahasiswa" name="mahasiswa[]" required>
                @foreach($mahasiswa as $m)
                    <option value="{{ $m->id }}" {{ in_array($m->id, $selectedMahasiswa) ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>


                        <!-- Judul KoTA -->
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
                                    <input name="judul" value="{{ old('judul', $kota->judul) }}" type="text"
                                        class="form-control" placeholder="Masukan Judul" required />
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
                                    <input name="periode" value="{{ old('periode', $kota->periode) }}" type="text"
                                        class="form-control" placeholder="Masukan Periode" required />
                                </div>
                            </div>
                        </div>

                        <!-- Kelas -->
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
                                    <option value="1" {{ old('kelas', $kota->kelas) == 1 ? 'selected' : '' }}>D3-A</option>
                                    <option value="2" {{ old('kelas', $kota->kelas) == 2 ? 'selected' : '' }}>D3-B</option>
                                    <option value="3" {{ old('kelas', $kota->kelas) == 3 ? 'selected' : '' }}>D4-A</option>
                                    <option value="4" {{ old('kelas', $kota->kelas) == 4 ? 'selected' : '' }}>D4-B</option>
                                </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tahapan Progres -->
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
                                    <input name="tahapan_progres"
                                        value="{{ old('tahapan_progres', $kota->tahapan_progres) }}" type="text"
                                        class="form-control" placeholder="Masukan Tahapan Progres" required />
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
            </form>
            <br />
            <br />
            <br />
        </div>
    </div>
</div>
@endsection
