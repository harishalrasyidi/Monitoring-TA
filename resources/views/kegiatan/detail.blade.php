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
                        <!-- <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
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
                        </div>   -->
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
                                        <h2 class="text-primary" data-toggle="modal" data-target="#editMetodologiModal{{ $item->id }}">
                                                {{ $item->nama_metodologi }}
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
                            // content: `Event ${event.id}`,
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
            </script>
        </body>
        </html>
    </div>
@endsection
