@extends('adminlte.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1 class="m-0">Jadwal Kesediaan Penguji</h1>
                </div>
                @if (auth()->user()->role == "1")
                <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahJadwalModal">
                        Tambah
                        <i class="nav-icon fas fa-plus"></i>
                    </button>
                    <div class="modal fade" id="tambahJadwalModal" tabindex="-1" role="dialog" aria-labelledby="tambahJadwalModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('jadwal.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="tambahJadwalModalLabel">Tambah Jadwal</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @if($errors->has('range_tanggal'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('range_tanggal') }}
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="nama_penguji">Nama Penguji</label>
                                            <select class="form-control" id="nama_penguji" name="nama_penguji">
                                                <option value="" disabled selected>Pilih Penguji</option>
                                                @foreach($users as $user)
                                                    @if($user->role == 2)
                                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_mulai">Tanggal Mulai</label>
                                            <input type="datetime-local" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_selesai">Tanggal Selesai</label>
                                            <input type="datetime-local" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="" disabled selected>Pilih Status</option>
                                                <option value="0">Perlu Konfirmasi</option>
                                                <option value="1">Sudah Fix</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <hr>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->

        @foreach($jadwals as $jadwal)
            <div class="modal fade" id="editJadwalModal{{ $jadwal->id_jadwal }}" tabindex="-1" aria-labelledby="editJadwalModalLabel{{ $jadwal->id_jadwal }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('jadwal.update', $jadwal->id_jadwal) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editJadwalModalLabel{{ $jadwal->id_jadwal }}">Edit Jadwal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nama_penguji">Nama Penguji</label>
                                    <select class="form-control" id="nama_penguji" name="nama_penguji">
                                        @foreach($users as $user)
                                            @if($user->role == 2)
                                                <option value="{{ $user->name }}" {{ $user->name == $jadwal->nama_penguji ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                               
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('Y-m-d\TH:i')) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('Y-m-d\TH:i')) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="0" {{ $jadwal->status == 0 ? 'selected' : '' }}>Perlu Konfirmasi</option>
                                        <option value="1" {{ $jadwal->status == 1 ? 'selected' : '' }}>Sudah Fix</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        
        @foreach($jadwals as $jadwal)
            <div class="modal fade" id="deleteJadwalModal{{ $jadwal->id_jadwal }}" tabindex="-1" role="dialog" aria-labelledby="deleteJadwalModalLabel{{ $jadwal->id_jadwal }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteJadwalModalLabel{{ $jadwal->id_jadwal }}">Hapus Jadwal</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('jadwal.destroy', $jadwal->id_jadwal) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p>Apakah anda yakin ingin menghapus jadwal "{{ $jadwal->nama_penguji }}"?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- /.content -->
    <br>
    <br>
    <br>
</div>
<!-- /.content-wrapper -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: {!! json_encode($jadwals->map(function($jadwal) {
            return [
                'id' => $jadwal->id_jadwal,
                'title' => $jadwal->nama_penguji,
                'start' => $jadwal->tanggal_mulai,
                'end' => $jadwal->tanggal_selesai,
                'backgroundColor' => $jadwal->status == 1 ? '#28a745' : '#007bff', // Hijau jika sudah fix, biru jika belum fix
                'borderColor' => $jadwal->status == 1 ? '#28a745' : '#007bff',
                'extendedProps' => [
                    'deleteIcon' => '<i class="fa fa-trash" style="color: red;"></i>'
                ]
            ];
        })) !!},
        editable: false,
        eventContent: function(arg) {
            var eventTitle = document.createElement('div');
            eventTitle.innerHTML = arg.event.title;
            
            var deleteIcon = document.createElement('span');
            deleteIcon.innerHTML = arg.event.extendedProps.deleteIcon;
            deleteIcon.style.cursor = 'pointer';
            deleteIcon.addEventListener('click', function(e) {
                e.stopPropagation();  // Menghentikan propagasi event click ke FullCalendar
                $('#deleteJadwalModal' + arg.event.id).modal('show');
            });
            
            var arrayOfDomNodes = [eventTitle];
            @if(auth()->user()->role == "1")
            arrayOfDomNodes.push(deleteIcon);
            @endif
            return { domNodes: arrayOfDomNodes };
        },
        eventClick: function(info) {
            @if(auth()->user()->role == "1")
            $('#editJadwalModal' + info.event.id).modal('show');
            @endif
        },
        eventDrop: function(info) {
            var eventId = info.event.id;
            var start = info.event.start.toISOString();
            var end = info.event.end.toISOString();

            // Update event via AJAX
            $.ajax({
                url: '{{ route("jadwal.update", ":id_jadwal") }}'.replace(':id_jadwal', eventId),
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal_mulai: start,
                    tanggal_selesai: end
                },
                success: function(response) {
                    console.log('Event updated:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error updating event:', error);
                }
            });
        },
        eventReceive: function(info) {
            var eventId = info.event.id;

            // Delete event via AJAX
            $.ajax({
                url: '{{ route("jadwal.destroy", ":id_jadwal") }}'.replace(':id_jadwal', eventId),
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Event deleted:', response);
                    info.event.remove();
                    $('#deleteJadwalModal' + eventId).modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting event:', error);
                    // location.reload();
                }
            });
        }
    });
    calendar.render();
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if($errors->has('range_tanggal')){
            if(session('modal') == 'tambah') {
                $('#tambahJadwalModal').modal('show');
            }elseif (session('modal') == 'edit') {
                $('#editJadwalModal').modal('show');
            }
        }
    });
</script>

@endsection
