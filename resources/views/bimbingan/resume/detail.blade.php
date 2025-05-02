@extends('adminlte.layouts.app')

@section('content')
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 col-md-8">
                    <h1 class="m-0">Detail Resume Bimbingan</h1>
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
                    <div class="card">
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                    <div>
                                        Kota Berhasil Diubah
                                    </div>
                            </div> 
                            @elseif(session('successdelete'))
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                    <div>
                                        Kota Berhasil Didelete
                                    </div>
                            </div>
                            @endif
                            <div class="row">
                                <!-- TAHAPAN PROGRES -->
                                <div class="col">
                                    <h5>Tahapan Progres</h5>
                                    <input class="form-control" value="{{ $tahapan_progres }}" readonly/> 
                                </div>
                            </div>
                            <br>
                            <div>
                                <h5>Resume Bimbingan</h5>
                                <small>{{ $resumes->isi_resume_bimbingan }}</small>
                            </div>
                            <br>
                            <div>
                                <h5>Revisi</h5>
                                <small>{{ $resumes->isi_revisi_bimbingan }}</small>
                            </div>
                            <br>
                        </div>
                    </div>
                <br/>
                <br/>
                <br/>
            </div>
        </div>

    </div>
<!-- End of Main Content -->
@endsection
