<!DOCTYPE html>
<html>
<head>
    <title>Resume Bimbingan</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .header .title {
            text-align: center;
            flex: 1;
            font-size: 20px;
        }
        .header table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }
        th{
            padding: 8px;
            text-align: center;
            page-break-inside: avoid;
        }
        td{
            padding: 8px;
            text-align: left;
            page-break-inside: avoid;
        }
        .long-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            page-break-inside: avoid;
        }
        @media print {
            .header, .header table, th, td {
                font-size: 20px;
            }
            .container {
                page-break-after: always;
            }
            .long-text {
                page-break-before: auto;
                page-break-inside: avoid;
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">
                        @php
                            $imagePath = public_path('assets/dist/img/polban1.png');
                            $imageData = base64_encode(file_get_contents($imagePath));
                        @endphp
                        <img src="data:image/jpeg;base64, {{ $imageData }}" alt="Polban" width="100">
                        </th>
                        <th colspan="2">URUSAN TEKNIK KOMPUTER DAN INFORMATIKA POLITEKNIK NEGERI BANDUNG</th>
                    </tr>
                    <tr>
                        <th>FORMULIR TUGAS AKHIR</th>
                        <th>FTA.05.a / 09.a</th>
                    </tr>
                </thead>
            </table>
            <br>
            <h2 class="title">RESUME BIMBINGAN</h2>
            <br>
        </div>
        <table>
            <tbody>
                <tr>
                    <th>Bimbingan Ke</th>
                    <td>{{ $dosen->nama_dosen }}</td>
                    <th>Jam Mulai</th>
                    <td>{{ $resume->jam_mulai }}</td>
                    <th>Paraf Pembimbing</th>
                </tr>
                <tr>
                    <th>Tanggal Bimbingan</th>
                    <td>{{ $resume->tanggal_bimbingan }}</td>
                    <th>Jam Selesai</th>
                    <td>{{ $resume->jam_selesai }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5" class="long-text">{{ $resume->isi_resume_bimbingan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
