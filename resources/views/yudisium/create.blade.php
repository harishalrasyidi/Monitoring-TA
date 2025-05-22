@extends('adminlte.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col">
          <h1 class="m-0">Tambah Data Yudisium</h1>
        </div><!-- /.col -->
        <div class="col d-flex justify-content-end">
          <div class="btn-group">
            <a href="{{ route('yudisium.index') }}" class="btn btn-secondary">Kembali</a>
          </div>
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
          <h3 class="card-title">Form Tambah Data Yudisium</h3>
        </div>
        <div class="card-body">
          <form action="{{ route('yudisium.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="id_kota">KoTA <span class="text-danger">*</span></label>
              <select name="id_kota" id="id_kota" class="form-control select2 @error('id_kota') is-invalid @enderror" required>
                <option value="">-- Pilih KoTA --</option>
                @foreach($kota as $k)
                <option value="{{ $k->id_kota }}">{{ $k->nama_kota }} - {{ $k->judul }}</option>
                @endforeach
              </select>
              @error('id_kota')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="kategori_yudisium">Kategori Yudisium <span class="text-danger">*</span></label>
              <select name="kategori_yudisium" id="kategori_yudisium" class="form-control @error('kategori_yudisium') is-invalid @enderror" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="1">Yudisium 1</option>
                <option value="2">Yudisium 2</option>
                <option value="3">Yudisium 3</option>
              </select>
              @error('kategori_yudisium')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="tanggal_yudisium">Tanggal Yudisium <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_yudisium" id="tanggal_yudisium" class="form-control @error('tanggal_yudisium') is-invalid @enderror" required>
              @error('tanggal_yudisium')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="nilai_akhir">Nilai Akhir</label>
              <input type="number" step="0.01" min="0" max="4" name="nilai_akhir" id="nilai_akhir" class="form-control @error('nilai_akhir') is-invalid @enderror" placeholder="Contoh: 3.75">
              @error('nilai_akhir')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="status">Status <span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                <option value="">-- Pilih Status --</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
              </select>
              @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="keterangan">Keterangan</label>
              <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Masukkan keterangan jika ada"></textarea>
              @error('keterangan')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ route('yudisium.index') }}" class="btn btn-default">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

@push('scripts')
<script>
  $(function() {
    $('.select2').select2({
      theme: 'bootstrap4',
      width: '100%'
    });
  });
</script>
@endpush

@endsection