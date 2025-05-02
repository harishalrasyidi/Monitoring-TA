@extends('adminlte.layouts.app')

@section('content')
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 col-md-8">
                    <h1 class="m-0">Edit Resume Bimbingan</h1>
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
                <form action="{{ route('resume.update', $resume->id_resume_bimbingan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            @if (isset($errors) && $errors->any())
                                <div class="col-md-12">
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-warning" role="alert">
                                            <i class="mdi mdi-alert-outline me-2"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- DOSEN -->
                            <div class="mb-3">
                                <label for="dosen">Dosen Pembimbing</label>
                                <select class="form-control" id="dosen" name="dosen" required>
                                    <option value='' disabled>Pilih Dosen</option>
                                    @foreach($dosen as $d)
                                        <option value="{{ $d->id }}" {{ $dosen_terkait->id_dosen_terkait == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="sesi_bimbingan" class="form-label">Sesi Bimbingan</label>
                                    <input name="sesi_bimbingan" value="{{ old('sesi_bimbingan', $resume->sesi_bimbingan) }}" type="text" class="form-control" id="sesi_bimbingan" placeholder="Sesi Bimbingan Ke-"/>
                                </div>
                                <!-- TAHAPAN PROGRES -->
                                <div class="col mb-3">
                                    <label for="tahapan_progres" class="form-label">Tahapan Progres</label>
                                    <select name="tahapan_progres" class="form-control" id="tahapan_progres" required>
                                        <option value="" disabled {{ old('tahapan_progres', $resume->tahapan_progres) === null ? 'selected' : '' }}>Pilih Tahapan Progres</option>
                                        <option value="1" {{ old('tahapan_progres', $resume->tahapan_progres) == 1 ? 'selected' : '' }}>Seminar 2</option>
                                        <option value="2" {{ old('tahapan_progres', $resume->tahapan_progres) == 2 ? 'selected' : '' }}>Seminar 3</option>
                                        <option value="3" {{ old('tahapan_progres', $resume->tahapan_progres) == 3 ? 'selected' : '' }}>Sidang</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2">
                                <!-- TANGGAL BIMBINGAN -->
                                <div class="col mb-3">
                                    <label for="tanggal_bimbingan" class="form-label">Tanggal</label>
                                    <input name="tanggal_bimbingan" value="{{ old('tanggal_bimbingan', $resume->tanggal_bimbingan) }}" type="date" class="form-control" id="tanggal_bimbingan" placeholder="dd/mm/yy" required/>
                                </div>

                                <!-- WAKTU BIMBINGAN -->
                                <div class="col mb-3">
                                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                    <input name="jam_mulai" value="{{ old('jam_mulai', $resume->jam_mulai) }}" type="time" class="form-control" id="jam_mulai" placeholder="hh:mm" required/>
                                </div>

                                <div class="col mb-3">
                                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                    <input name="jam_selesai" value="{{ old('jam_selesai', $resume->jam_selesai) }}" type="time" class="form-control" id="jam_selesai" placeholder="hh:mm" required/>
                                </div>
                            </div>

                            <!-- ISI RESUME BIMBINGAN -->
                            <div class="mb-3">
                                <label for="isi_resume_bimbingan" class="form-label">Resume Bimbingan</label>
                                <textarea name="isi_resume_bimbingan" class="form-control" id="isi_resume_bimbingan" rows="3" required>{{ old('isi_resume_bimbingan', $resume->isi_resume_bimbingan) }}</textarea>
                            </div>

                            <!-- ISI REVISI BIMBINGAN -->
                            <div class="mb-3">
                                <label for="isi_revisi_bimbingan" class="form-label">Revisi Bimbingan</label>
                                <textarea name="isi_revisi_bimbingan" class="form-control" id="isi_revisi_bimbingan" rows="3">{{ old('isi_revisi_bimbingan', $resume->isi_revisi_bimbingan) }}</textarea>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto me-auto"></div>
                        <div class="col-auto" style="margin-left: auto;">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
