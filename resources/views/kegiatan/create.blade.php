@extends('adminlte.layouts.app')

@section('content')
    <!-- Pembungkus Konten. Berisi konten halaman -->
    <div class="content-wrapper">
        <!-- Header Konten (Judul halaman) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col">
                        <h1 class="m-0">Tambah Kegiatan</h1>
                    </div>
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                    </div>  
                </div><!-- /.row -->
                <hr/>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.header konten -->

        <!-- Konten Utama -->
        <div class="content">
            <!-- Memulai Konten Halaman -->
            <div class="container-fluid">
                <!-- Formulir Tambah Kegiatan -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="{{ route('kegiatan.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="jenis_label">Jenis Label</label>
                                <input type="text" name="jenis_label" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_kegiatan">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="bulan">Bulan</label>
                                <input type="number" name="bulan" class="form-control" min="1" max="12" required>
                            </div>
                            <div class="form-group">
                                <label for="minggu">Minggu</label>
                                <input type="number" name="minggu" class="form-control" min="1" max="4" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="selesai">Selesai</option>
                                    <option value="belum_selesai">Belum Selesai</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Kegiatan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
