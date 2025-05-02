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
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#kegiatanModal">
                                Tambah Kegiatan
                            </button>
                        </div>
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#statusModal">
                                Status Pengerjaan
                            </button>
                        </div>
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addMetodologiModal">
                                Tambah Metodologi
                            </button>
                        </div>
                    </div>  
                </div><!-- /.row -->
                <hr/>
            </div><!-- /.container-fluid -->
        </div>
        <html lang="en">
        <head>

            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Timeline</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet" type="text/css" />
            <style>
                .completed { background-color: green; }
                .pending { background-color: blue; }
                .bold-resource { font-weight: bold; }
                .group-header {
                    display: flex;
                    justify-content: space-between;
                    padding: 5px;
                    background-color: #f0f0f0;
                    border-bottom: 1px solid #ddd;
                }
                #header-container {
                    display: flex;
                    align-items: center;
                }
                .vis-item .delete-icon {
                    cursor: pointer;
                    color: red;
                    margin-left: 10px;
                }
            </style>

        </head>
        <body>
            <div class="content">
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="btn-group mr-2">
                                <h2>Metodologi Pengembangan :  
                                    @foreach($kota_metodologi as $item)
                                        <h2>
                                            <a href="#" class="text-primary" data-toggle="modal" data-target="#editMetodologiModal{{ $item->id }}">
                                                {{ $item->nama_metodologi }}
                                            </a>
                                        </h2>
                                    @endforeach
                                </h2>
                            </div>
                                <div id="visualization"></div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>

<script>
    var data = @json($data);
    var progres = @json($tahapan_progres);
    var container = document.getElementById('visualization');
    var groups = new vis.DataSet(
        data.resource
            .sort((a, b) => a.id - b.id)
            .map(resource => ({
                id: resource.id,
                content: resource.nama_kegiatan,
                className: resource.jenis_label === 'bold' ? 'bold-resource' : ''
            }))
    );

    // Create a DataSet (allows two way data-binding)
    var items = new vis.DataSet(
        data.events
            .sort((a, b) => a.id - b.id)
            .map(event => ({
                id: event.id,
                group: event.id_nama_kegiatan,
                content: `
                            <div class="timeline-item-content">
                                <button class="delete-item" data-id="${event.id}">🗑️</button>
                            </div>
                        `,
                start: event.tanggal_mulai,
                end: event.tanggal_selesai,
                className: event.status
            }))
    );

    var options = {
        orientation: {
            axis: 'top' // Show the axis on top
        },
        timeAxis: {
            scale: 'week', 
            step: 1,
        },
    };

    var timeline = new vis.Timeline(container, items, groups, options);

    // Tambahkan event listener untuk tombol hapus
    var selectedItemId = null;
    container.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-item')) {
            event.stopPropagation(); // Cegah event klik dari propagasi ke timeline
            selectedItemId = event.target.getAttribute('data-id');
            console.log('selectedItemId:', selectedItemId);

            var deleteEventModal = $('#deleteModal');
            deleteEventModal.find('input[name="id"]').val(selectedItemId);
            deleteEventModal.modal('show');

        }
    });

    // Tambahkan event listener untuk memilih item
    timeline.on('select', function(properties) {
        var clickedElement = document.elementFromPoint(properties.event.center.x, properties.event.center.y);
        if (clickedElement && clickedElement.classList.contains('delete-item')) {
            return; // Jangan proses jika tombol hapus diklik
        }

        var itemId = properties.items[0];
        var selectedItem = items.get(itemId);
        console.log('Item terpilih:', selectedItem);
        // Isi modal dengan data item yang dipilih
        var editEventModal = $('#editEventModal');
        editEventModal.find('input[name="id"]').val(selectedItem.id);
        editEventModal.find('input[name="group"]').val(selectedItem.group);
        editEventModal.find('input[name="start"]').val(selectedItem.start);
        editEventModal.find('input[name="end"]').val(selectedItem.end);
        // Tampilkan modal
        editEventModal.modal('show');
    });

    // Tambahkan event listener untuk klik grup
    timeline.on('click', function(properties) {
        var groupId = properties.group;
        if (groupId !== undefined) {
            var selectedGroup = groups.get(groupId);
            console.log('Grup diklik:', selectedGroup);
            var editEventModal = $('#editEventModal');
            editEventModal.find('input[name="nama"]').val(selectedGroup.content);
        }        
    });
</script>

            <!-- Modal Tambah Kegiatan-->
            <div class="modal fade" id="kegiatanModal" aria-labelledby="kegiatanModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="kegiatanModalLabel">Tambah Kegiatan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Form untuk mengisi nama kegiatan -->
                            <form action="{{ route('kegiatan.store_kegiatan') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="id_timeline" class="form-label">Target Pengerjaan</label>
                                    <select class="form-control" id="id_timeline" name="id_timeline" required>
                                        <option value="" disabled selected>Pilih Target Pengerjaan</option>
                                        @foreach($tahapan_progres as $progres)
                                            <option value="{{ $progres->id_timeline }}">{{ $progres->nama_kegiatan }} ({{$progres->tanggal_mulai}} sampai {{$progres->tanggal_selesai}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- <div class="form-group">
                                <label for="type" class="form-label">Jenis Kegiatan</label>
                                <select class="form-control" id="jenis_label" name="jenis_label" required>
                                    <option value='bold' selected>Tahap Pelaksanaan</option>
                                    <option value='normal' selected>Tahapan Kegiatan</option>
                                </select>
                                </div> -->
                                <div class="form-group">
                                    <label for="nama_kegiatan">Nama Kegiatan</label>
                                    <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Kegiatan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit status -->
            <div class="modal fade" id="statusModal"  aria-labelledby="statusModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel">Ubah Status Pengerjaan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('kegiatan.storeStatusKegiatan') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                <label for="id" class="form-label">Status Pengerjaan</label>
                                    <select class="form-control" id="id" name="id" required>
                                    <option value="" disabled selected>Pilih Nama Kegiatan</option>
                                        @foreach($status as $nama )
                                            <option value="{{ $nama->id }}">{{ $nama->nama_kegiatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                <label for="status">Status:</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="" disabled selected>Status Pengerjaan</option>
                                    <option value="completed" >Selesai</option>
                                    <option value="pending" >Belum Selesai</option>
                                </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Status Kegiatan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Metodologi -->
            <div class="modal fade" id="addMetodologiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('metodologi.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Metodologi Kota</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_metodologi">Metodologi</label>
                                    <select class="form-control" id="id_metodologi" name="id_metodologi" required>
                                        <option value="" disabled selected>Pilih Metodologi</option>
                                        @foreach($metodologi as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_metodologi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Metodologi-->
            @foreach($kota_metodologi as $item)
                <div class="modal fade" id="editMetodologiModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('metodologi.update', $item->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Edit Metodologi Kota</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="id_metodologi">Metodologi</label>
                                        <select class="form-control" id="id_metodologi" name="id_metodologi" required>
                                            <option value="" disabled selected>Pilih Metodologi</option>
                                            @foreach($metodologi as $metodo)
                                                <option value="{{ $metodo->id }}" {{ $metodo->id == $item->id_metodologi ? 'selected' : '' }}>{{ $metodo->nama_metodologi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Edit Event Modal -->
            <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editEventForm" action="{{ route('events.edit') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id">
                                <input type="hidden" name="group">
                                <div class="form-group">
                                    <label for="nama">Nama Kegiatan</label>
                                    <input type="text" name="nama" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="start">Start</label>
                                    <input type="date" name="start" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="end">End</label>
                                    <input type="date" name="end" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Delete -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <form id="deleteModal" action="{{ route('delete.item')}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <input type="hidden" name="id">
                            <p>Apakah anda yakin ingin menghapus jadwal ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </body>
        </html>
    </div>
@endsection
