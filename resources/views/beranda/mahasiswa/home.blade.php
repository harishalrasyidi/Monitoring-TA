@extends('adminlte.layouts.app')

@section('content')

<div class="content-wrapper">
  <h3 class="mb-4">Dashboard Mahasiswa</h3>
  <hr>

  <!-- Kartu Ringkasan -->
  <div class="row text-center">
    <div class="col-md-3">
      <div class="card clickable-card" data-toggle="modal" data-target="#anggotaModal" style="cursor: pointer;">
        <div class="card-body">
          <i class="fas fa-users fa-2x text-primary mb-2"></i>
          <h6>Anggota Kelompok</h6>
          <h3>{{ $anggotaKelompok->count() }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card clickable-card" data-toggle="modal" data-target="#dosbingModal" style="cursor: pointer;">
        <div class="card-body">
          <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
          <h6>Dosen Pembimbing</h6>
          <h3>{{ $dosbing->count() }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card clickable-card" data-toggle="modal" data-target="#pengujiModal" style="cursor: pointer;">
        <div class="card-body">
          <i class="fas fa-user-tie fa-2x text-warning mb-2"></i>
          <h6>Dosen Penguji</h6>
          <h3>{{ $penguji->count() }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <i class="fas fa-file-alt fa-2x text-info mb-2"></i>
          <h6>Total Artefak</h6>
          <h3>{{ $seminar1->count() + $seminar2->count() + $seminar3->count() + $sidang->count() }}</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Detail Artefak per Tahapan -->
  <div class="row mt-4">
    <!-- Seminar 1 -->
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <strong>Artefak Seminar 1</strong>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Nama Artefak</th>
                <th>Status</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @forelse($seminar1 as $no => $artefak)
                <tr>
                  <td>{{ $no+1 }}</td>
                  <td>{{ $artefak->nama_artefak }}</td>
                  <td>
                    @if($artefak->file_pengumpulan)
                      <span class="badge badge-success">Sudah Upload</span>
                    @else
                      <span class="badge badge-warning">Belum Upload</span>
                    @endif
                  </td>
                  <td>
                    @if($artefak->file_pengumpulan)
                        <a href="{{ asset('storage/' . $artefak->file_pengumpulan) }}" target="_blank" class="btn btn-sm btn-primary">                        
                            <i class="fas fa-eye"></i> Lihat
                      </a>
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">Data belum ada</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Seminar 2 -->
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <strong>Artefak Seminar 2</strong>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Nama Artefak</th>
                <th>Status</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @forelse($seminar2 as $no => $artefak)
                <tr>
                  <td>{{ $no+1 }}</td>
                  <td>{{ $artefak->nama_artefak }}</td>
                  <td>
                    @if($artefak->file_pengumpulan)
                      <span class="badge badge-success">Sudah Upload</span>
                    @else
                      <span class="badge badge-warning">Belum Upload</span>
                    @endif
                  </td>
                  <td>
                    @if($artefak->file_pengumpulan)
                        <a href="{{ asset('storage/' . $artefak->file_pengumpulan) }}" target="_blank" class="btn btn-sm btn-primary">                        
                            <i class="fas fa-eye"></i> Lihat
                      </a>
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">Data belum ada</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <!-- Seminar 3 -->
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <strong>Artefak Seminar 3</strong>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Nama Artefak</th>
                <th>Status</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @forelse($seminar3 as $no => $artefak)
                <tr>
                  <td>{{ $no+1 }}</td>
                  <td>{{ $artefak->nama_artefak }}</td>
                  <td>
                    @if($artefak->file_pengumpulan)
                      <span class="badge badge-success">Sudah Upload</span>
                    @else
                      <span class="badge badge-warning">Belum Upload</span>
                    @endif
                  </td>
                  <td>
                   @if($artefak->file_pengumpulan)
                        <a href="{{ asset('storage/' . $artefak->file_pengumpulan) }}" target="_blank" class="btn btn-sm btn-primary">                        
                            <i class="fas fa-eye"></i> Lihat
                      </a>
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">Data belum ada</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Sidang -->
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <strong>Artefak Sidang</strong>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Nama Artefak</th>
                <th>Status</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @forelse($sidang as $no => $artefak)
                <tr>
                  <td>{{ $no+1 }}</td>
                  <td>{{ $artefak->nama_artefak }}</td>
                  <td>
                    @if($artefak->file_pengumpulan)
                      <span class="badge badge-success">Sudah Upload</span>
                    @else
                      <span class="badge badge-warning">Belum Upload</span>
                    @endif
                  </td>
                  <td>
                    @if($artefak->file_pengumpulan)
                        <a href="{{ asset('storage/' . $artefak->file_pengumpulan) }}" target="_blank" class="btn btn-sm btn-primary">                        
                            <i class="fas fa-eye"></i> Lihat
                      </a>
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">Data belum ada</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Anggota Kelompok -->
<div class="modal fade" id="anggotaModal" tabindex="-1" role="dialog" aria-labelledby="anggotaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="anggotaModalLabel">
          <i class="fas fa-users text-primary"></i> Anggota Kelompok
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
              @forelse($anggotaKelompok as $no => $anggota)
                <tr>
                  <td>{{ $no+1 }}</td>
                  <td>{{ $anggota->name }}</td>
                  <td>{{ $anggota->nomor_induk ?? '-' }}</td>
                  <td>{{ $anggota->email }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">Data belum ada</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Dosen Pembimbing -->
<div class="modal fade" id="dosbingModal" tabindex="-1" role="dialog" aria-labelledby="dosbingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dosbingModalLabel">
          <i class="fas fa-chalkboard-teacher text-success"></i> Dosen Pembimbing
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
              @forelse($dosbing as $no => $dosen)
                <tr>
                  <td>{{ $no+1 }}</td>
                  <td>{{ $dosen->name }}</td>
                  <td>{{ $dosen->email }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center">Data belum ada</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Dosen Penguji -->
<div class="modal fade" id="pengujiModal" tabindex="-1" role="dialog" aria-labelledby="pengujiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pengujiModalLabel">
          <i class="fas fa-user-tie text-warning"></i> Dosen Penguji
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
              @forelse($penguji as $no => $dosen)
                <tr>
                  <td>{{ $no+1 }}</td>
                  <td>{{ $dosen->name }}</td>
                  <td>{{ $dosen->email }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center">Data belum ada</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('styles')
<style>
.clickable-card {
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.clickable-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.clickable-card:active {
  transform: translateY(0);
}
</style>
@endsection