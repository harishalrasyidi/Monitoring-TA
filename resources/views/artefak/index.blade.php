@extends('adminlte.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col">
                        <h1 class="m-0">Daftar Artefak</h1>
                    </div>
                    @if (auth()->user()->role == "1")
                        <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahArtefakModal">
                                Tambah
                                <i class="nav-icon fas fa-plus"></i>
                            </button>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="tambahArtefakModal" tabindex="-1" role="dialog" aria-labelledby="tambahArtefakModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('artefak.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tambahArtefakModalLabel">Tambah Artefak</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="nama_artefak">Nama Artefak</label>
                                                    <select name="nama_artefak" id="nama_artefak" class="form-control" required>
                                                        <option value="" disabled selected>Pilih Nama Artefak</option>
                                                        @if(isset($masterArtefaks))
                                                            @foreach($masterArtefaks as $masterArtefak)
                                                                <option value="{{ $masterArtefak }}" {{ old('nama_artefak') == $masterArtefak ? 'selected' : '' }}>{{ $masterArtefak }}</option>
                                                            @endforeach
                                                        @endif
                                                        <option value="Abstrak TA" {{ old('nama_artefak') == 'Abstrak TA' ? 'selected' : '' }}>Abstrak TA</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="deskripsi">Deskripsi</label>
                                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi artefak..." required>{{ old('deskripsi') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kategori_artefak">Kategori Artefak</label>
                                                    <select name="kategori_artefak" id="kategori_artefak" class="form-control" required>
                                                        <option value="" disabled selected>Pilih Kategori Artefak</option>
                                                        <option value="FTA" {{ old('kategori_artefak') == 'FTA' ? 'selected' : '' }}>FTA</option>
                                                        <option value="Dokumen" {{ old('kategori_artefak') == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
                                                        <option value="Teks" {{ old('kategori_artefak') == 'Teks' ? 'selected' : '' }}>Teks</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tanggal_tenggat">Tanggal Tenggat</label>
                                                    <input type="date" class="form-control" id="tanggal_tenggat" name="tanggal_tenggat" value="{{ old('tanggal_tenggat') }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="waktu_tenggat">Waktu Tenggat</label>
                                                    <input type="time" class="form-control" id="waktu_tenggat" name="waktu_tenggat" value="{{ old('waktu_tenggat') }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div><!-- /.row -->
                <hr/>
            </div><!-- /.container-fluid -->
        </div>

        <!-- Main content -->
        <div class="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- DataTables Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row mt-4">
                            @if(isset($artefaks) && count($artefaks) > 0)
                                @foreach($artefaks as $artefak)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="col">
                                                    <h4>{{ $artefak->nama_artefak ?? 'Nama tidak tersedia' }}</h4>
                                                    @if(!empty($artefak->kategori_artefak))
                                                        <span class="badge badge-info">{{ $artefak->kategori_artefak }}</span>
                                                    @endif
                                                </div>
                                                @if (auth()->user()->role == "1")
                                                    <div class="col d-flex justify-content-end">
                                                        <div class="mr-2">
                                                            <!-- Button edit -->
                                                            <a href="#" class="edit-artefak mr-2" data-placement="top" title="Edit Artefak" data-toggle="modal" data-target="#editArtefakModal{{ $artefak->id_artefak }}">
                                                                <i class="nav-icon fas fa-pen" style="color: blue;"></i>
                                                            </a>
                                                            
                                                            <!-- Modal Edit Artefak -->
                                                            <div class="modal fade" id="editArtefakModal{{ $artefak->id_artefak }}" tabindex="-1" aria-labelledby="editArtefakModalLabel{{ $artefak->id_artefak }}" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('artefak.update', $artefak->id_artefak) }}" method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="editArtefakModalLabel{{ $artefak->id_artefak }}">Edit Artefak</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <label for="nama_artefak_edit">Nama Artefak</label>
                                                                                    <input type="text" class="form-control" id="nama_artefak_edit" name="nama_artefak" value="{{ old('nama_artefak', $artefak->nama_artefak ?? '') }}" required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="deskripsi_edit">Deskripsi</label>
                                                                                    <textarea class="form-control" id="deskripsi_edit" name="deskripsi" rows="3" required>{{ old('deskripsi', $artefak->deskripsi ?? '') }}</textarea>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="kategori_artefak_edit">Kategori Artefak</label>
                                                                                    <select name="kategori_artefak" id="kategori_artefak_edit" class="form-control" required>
                                                                                        <option value="" disabled>Pilih Kategori Artefak</option>
                                                                                        <option value="FTA" {{ old('kategori_artefak', $artefak->kategori_artefak ?? '') == 'FTA' ? 'selected' : '' }}>FTA</option>
                                                                                        <option value="Dokumen" {{ old('kategori_artefak', $artefak->kategori_artefak ?? '') == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
                                                                                        <option value="Teks" {{ old('kategori_artefak', $artefak->kategori_artefak ?? '') == 'Teks' ? 'selected' : '' }}>Teks</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="tenggat_waktu_edit">Tenggat Waktu</label>
                                                                                    <input type="datetime-local" class="form-control" id="tenggat_waktu_edit" name="tenggat_waktu" value="{{ old('tenggat_waktu', $artefak->tenggat_waktu ?? '') }}" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <a href="#" data-placement="top" title="Delete Artefak" data-toggle="modal" data-target="#deleteArtefakModal-{{ $artefak->id_artefak }}">
                                                                <i class="nav-icon fas fa-trash" style="color: red;"></i>
                                                            </a>
                                                            
                                                            <!-- Delete Modal -->
                                                            <div class="modal fade" id="deleteArtefakModal-{{ $artefak->id_artefak }}" tabindex="-1" role="dialog" aria-labelledby="deleteArtefakModalLabel-{{ $artefak->id_artefak }}" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="deleteArtefakModalLabel-{{ $artefak->id_artefak }}">Konfirmasi Hapus</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Apakah Anda yakin ingin menghapus artefak "<strong>{{ $artefak->nama_artefak ?? 'Nama tidak tersedia' }}</strong>"?</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <form action="{{ route('artefak.destroy', $artefak->id_artefak) }}" method="POST" style="display:inline;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="card-body">
                                                <p class="card-text">{{ $artefak->deskripsi ?? 'Deskripsi tidak tersedia' }}</p>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        Tenggat: <strong>{{ $artefak->formatted_tenggat_waktu ?? ($artefak->tenggat_waktu ?? 'Tidak ditentukan') }}</strong>
                                                    </small>
                                                </p>
                                                
                                                @if (auth()->user()->role == "3")
                                                    @if (($artefak->kategori_artefak ?? '') == 'Teks')
                                                        <!-- Form untuk input teks (Abstrak TA) -->
                                                        @if (isset($artefak->kumpul) && !empty($artefak->kumpul->teks_pengumpulan))
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <h6 class="card-title">Teks yang dikumpulkan:</h6>
                                                                    <p class="card-text">{{ $artefak->kumpul->teks_pengumpulan }}</p>
                                                                    <form action="{{ route('submissions.destroy', $artefak->kumpul->id) }}" method="POST" class="mt-2">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus teks ini?')">Hapus Teks</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <form action="{{ route('submissions.store', $artefak->id_artefak) }}" method="POST">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label for="teks_pengumpulan_{{ $artefak->id_artefak }}">Masukkan Teks:</label>
                                                                    <textarea class="form-control" name="teks_pengumpulan" id="teks_pengumpulan_{{ $artefak->id_artefak }}" rows="4" placeholder="Masukkan teks abstrak TA di sini..." required></textarea>
                                                                </div>
                                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
                                                                    <button class="btn btn-primary" type="submit">Kumpulkan Teks</button>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <!-- Form untuk upload file (FTA/Dokumen) -->
                                                        @if (isset($artefak->kumpul) && !empty($artefak->kumpul->file_pengumpulan))
                                                            <div class="mb-3">
                                                                <a href="{{ Storage::url($artefak->kumpul->file_pengumpulan) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fas fa-file-pdf"></i> {{ basename($artefak->kumpul->file_pengumpulan) }}
                                                                </a>
                                                            </div>
                                                            <form action="{{ route('submissions.destroy', $artefak->kumpul->id) }}" method="POST" class="mt-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus file ini?')">Hapus File</button>
                                                                </div>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('submissions.store', $artefak->id_artefak) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label for="file_pengumpulan_{{ $artefak->id_artefak }}">Pilih File:</label>
                                                                    <input type="file" class="form-control-file" id="file_pengumpulan_{{ $artefak->id_artefak }}" name="file_pengumpulan" accept=".pdf,.doc,.docx,.txt" required>
                                                                </div>
                                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
                                                                    <button class="btn btn-primary" type="submit">Kumpulkan File</button>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <h5>Belum ada artefak yang tersedia</h5>
                                        <p>Silakan tambah artefak baru untuk memulai.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection