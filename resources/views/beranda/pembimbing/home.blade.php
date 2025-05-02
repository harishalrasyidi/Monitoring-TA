@extends('adminlte.layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h1 class="m-0">Daftar Kelompok Tugas Akhir</h1>
                </div>
                <div class="col d-flex justify-content-end">
                    <div class="btn-group mr-2">
                        <!-- Messages Dropdown Menu -->
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Kelas
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('kota', ['sort' => 'kelas', 'direction' => 'asc', 'value' => 1]) }}" class="dropdown-item">D3-A</a></li>
                                <li><a href="{{ route('kota', ['sort' => 'kelas', 'direction' => 'asc', 'value' => 2]) }}" class="dropdown-item">D3-B</a></li>
                                <li><a href="{{ route('kota', ['sort' => 'kelas', 'direction' => 'asc', 'value' => 3]) }}" class="dropdown-item">D4-A</a></li>
                                <li><a href="{{ route('kota', ['sort' => 'kelas', 'direction' => 'asc', 'value' => 4]) }}" class="dropdown-item">D4-B</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="btn-group mr-2">
                        <!-- Notifications Dropdown Menu -->
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" href="#">
                                Tahapan TA
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg">
                                <li><a href="#" class="dropdown-item">Seminar 1</a></li>
                                <li><a href="#" class="dropdown-item">Seminar 2</a></li>
                                <li><a href="#" class="dropdown-item">Seminar 3</a></li>
                                <li><a href="#" class="dropdown-item">Sidang</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="btn-group">
                        <!-- Messages Dropdown Menu -->
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Periode
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item">2024</a></li>
                                <li><a class="dropdown-item">2023</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
        <hr/>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
<!-- Begin Page Content -->
<div class="container-fluid">
<!-- DataTables Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-bordered data-table"  width="100%" cellspacing="0">
                <thead class="text-center" style="background-color: gray; color: white;">
                    <tr>
                        <th>No</th>
                        <th>Kode KoTA</th>
                        <th>Judul KoTA</th>
                        <th>Tahap Progres</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kotas as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $row->nama_kota }}</td>
                            <td>{{ $row->judul }}</td>
                            <td class="text-center">{{ $row->nama_tahapan }}</td>
                            <td class="text-center">
                                <a class="detail" href="{{ route('kegiatan.detail', $row->id_kota) }}" data-toggle="tooltip" data-placement="top" title="Jadwal Kegiatan KoTA"><i class="nav-icon fas fa-calendar" style="color: gray;"></i></a>                     
                                <a class="detail" href="{{ route('kota.detail', $row->id_kota) }}" data-toggle="tooltip" data-placement="top" title="Detail KoTA"><i class="nav-icon fas fa-eye" style="color: gray;"></i></a>
                                <a class="detail" href="{{ route('resume', $row->id_kota) }}" data-toggle="tooltip" data-placement="top" title="Resume KoTA"><i class="nav-icon fas fa-book" style="color: gray;"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                {{ $kotas->links() }}
            </ul>
        </nav>
    </div>
</div>
</div>

@endsection
