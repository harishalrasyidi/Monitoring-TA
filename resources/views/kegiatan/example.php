/.header konten
        <html>
        <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.14/index.global.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.14/index.global.min.js"></script>
        <style>
            .fc-event.completed {
                background-color: green;
                border-color: green;
            }
            .fc-event.pending {
                background-color: blue;
                border-color: blue;
            }
            .bold-resource {
                font-weight: bold;
            }
        </style>
        </head>
        <body>
        <div class="container">
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

            <!-- Tampilkan pesan sukses jika ada
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif -->

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
                                        @foreach($data['resource'] as $nama )
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col" id="calendar"></div>
        </div>

         <!-- Modal Edit -->
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Edit Event Modal -->
        <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="editEventForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id">
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Resource Modal -->
        <div class="modal fade" id="editResourceModal" tabindex="-1" role="dialog" aria-labelledby="editResourceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="editResourceForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editResourceModalLabel">Edit Resource</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script> 
            var data = @json($data);
            console.log(data.resource);
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                    plugins: ['resourceTimelineYearPlugin'],
                    initialView: 'resourceTimelineYear',
                    views: {
                        resourceTimelineYear: {
                            type: 'resourceTimeline',
                            duration: { years: 1 },
                            slotDuration: { weeks: 1 }, // Menampilkan kolom perminggu
                            slotLabelFormat: [
                                { month: 'short' }, // Format label bulan
                                { week: 'numeric' }, // Format label minggu
                                { day: 'numeric', month: 'numeric', omitZeroMinute: true }
                            ]
                        },
                    },
                    
                    resources: data.resource
                         .sort((a, b) => a.id - b.id)
                        .map(resource => ({
                        id: resource.id,
                        title: resource.nama_kegiatan,
                        classNames: resource.jenis_label === 'bold' ? 'bold-resource' : '',
                    })),
                    events: data.events
                        .sort((a, b) => a.id - b.id)
                        .map(event => ({
                        id: event.id,
                        resourceId: event.id_nama_kegiatan,
                        start: event.tanggal_mulai,
                        end: event.tanggal_selesai,
                        className: event.status,
                    })),
                    editable: true, // Enable editing
                    eventContent: function(arg) {
                        let deleteIcon = document.createElement('span');
                        deleteIcon.innerHTML = '<i class="fa fa-trash" style="color: red;"></i>'; // Ikon tempat sampah
                        deleteIcon.style.cursor = 'pointer';
                        deleteIcon.addEventListener('click', function(e) {
                            e.stopPropagation();
                            if (confirm('Are you sure you want to delete this event?')) {
                                let event = calendar.getEventById(arg.event.id);
                                event.remove(); // Hapus event dari kalender
                                
                                // Hapus event dari backend jika diperlukan
                                // Misalnya, dengan AJAX
                                $.ajax({
                                    url: '/delete-event/' + event.id,
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        alert('Event deleted successfully');
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                        alert('Error deleting event: ' + xhr.responseText);
                                    }
                                });
                            }
                        });

                        let title = document.createElement('span');
                        title.innerHTML = arg.event.title;

                        let arrayOfNodes = [ title, deleteIcon ];

                        return { domNodes: arrayOfNodes };
                    },
                    eventClick: function(info) {
                        // Buka modal untuk edit event
                        var event = info.event;
                        $('#editEventModal').modal('show');
                        $('#editEventForm [name="id"]').val(event.id);
                        $('#editEventForm [name="start"]').val(event.tanggal_mulai.toISOString().substring(0, 10));
                        $('#editEventForm [name="end"]').val(event.tanggal_selesai ? event.tanggal_selesai.toISOString().substring(0, 10) : '');
                    }
                });

                calendar.render();

                // Add event listener for click on resource
                calendarEl.addEventListener('click', function(event) {
                // Check if the clicked element is the delete icon
                var deleteIcon = event.target.closest('.delete-icon');
                if (deleteIcon) {
                    var resourceId = deleteIcon.dataset.resourceId;
                    var resource = calendar.getResourceById(resourceId);
                    if (resource) {
                        // Konfirmasi sebelum menghapus resource
                        if (confirm('Are you sure you want to delete this resource?')) {
                            calendar.getResourceById(resourceId).remove();
                            alert('Resource deleted successfully');
                        }
                    }
                } else {
                    // Check if the clicked element is within an event
                    var eventElement = event.target.closest('.fc-event');
                    if (!eventElement) {
                        var resourceRow = event.target.closest('.fc-resource');
                        if (resourceRow) {
                            var resourceId = resourceRow.dataset.resourceId;
                            var resource = calendar.getResourceById(resourceId);
                            if (resource) {
                                $('#editResourceModal').modal('show');
                                $('#editResourceForm [name="id"]').val(resource.id);
                                $('#editResourceForm [name="title"]').val(resource.nama_kegiatan);
                            }
                        }
                    }
                }
            });

                 // Handle form submission
                $('#editEventForm').on('submit', function(e) {
                    e.preventDefault();

                    var formData = $(this).serialize();

                    $.ajax({
                        url: '{{ route('events.edit') }}',
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if(response.success) {
                                $('#editResourceModal').modal('hide');
                                calendar.refetchResources(); // Metode untuk merefresh resource
                                alert('Resource updated successfully');
                            } else {
                                alert('Failed to update resource');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('Error updating resource: ' + xhr.responseText);
                        }
                    });
                });
                 // Handle resource form submission
                 $(document).ready(function() {
                    $('#editResourceForm').on('submit', function(e) {
                        e.preventDefault();

                        var formData = $(this).serialize();

                        $.ajax({
                            url: '{{ route('resources.edit') }}',
                            method: 'POST',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if(response.success) {
                                    $('#editResourceModal').modal('hide');
                                    calendar.refetchResources(); // Metode untuk merefresh resource
                                    alert('Resource updated successfully');
                                } else {
                                    alert('Failed to update resource');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                                alert('Error updating resource: ' + xhr.responseText);
                            }
                        });
                    });
                });
            });
        </script>
        </body>
        </html>