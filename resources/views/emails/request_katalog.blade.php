<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Katalog TA - Koordinator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            font-size: 13px;
        }
        .info-box {
            background-color: white;
            padding: 20px;
            border-left: 5px solid #007bff;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .priority-box {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-weight: bold;
            text-align: center;
        }
        .priority-urgent {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }
        .priority-tinggi {
            background-color: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }
        .priority-sedang {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 2px solid #bee5eb;
        }
        .priority-rendah {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .contact-info {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .katalog-info {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 5px solid #2196f3;
        }
        .deadline-alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .table-info td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .table-info td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f1f3f4;
            border-radius: 8px;
        }
        .anggota-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 Request Katalog TA</h1>
        <p>Permintaan Akses ke Koordinator TA Jurusan</p>
        <p><small>{{ $data['request_date'] }}</small></p>
        @if(!empty($data['request_id']))
        <p><small>ID Request: {{ $data['request_id'] }}</small></p>
        @endif
    </div>

    <div class="content">
        <p><strong>Yth. Koordinator TA Jurusan,</strong></p>
        
        <p>Anda menerima permintaan akses katalog TA dari mahasiswa/pemohon berikut:</p>

        <!-- Priority Alert -->
        @if(!empty($data['prioritas_level']))
        <div class="priority-box priority-{{ $data['prioritas_level'] }}">
            🚨 PRIORITAS: {{ strtoupper($data['prioritas']) }}
        </div>
        @endif

        <!-- Deadline Alert -->
        @if(!empty($data['deadline_request']))
        <div class="deadline-alert">
            ⏰ <strong>Deadline Kebutuhan:</strong> {{ $data['deadline_request'] }}
            <br><small>Pemohon membutuhkan akses sebelum tanggal tersebut.</small>
        </div>
        @endif

        <div class="contact-info">
            <h3>👤 Informasi Pemohon</h3>
            <table class="table-info">
                <tr>
                    <td>Nama:</td>
                    <td><strong>{{ $data['sender_name'] }}</strong></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $data['sender_email'] }}</td>
                </tr>
                <tr>
                    <td>NIM/ID:</td>
                    <td>{{ $data['sender_nim'] }}</td>
                </tr>
                @if(!empty($data['status_pemohon']))
                <tr>
                    <td>Status:</td>
                    <td><strong>{{ $data['status_pemohon'] }}</strong></td>
                </tr>
                @endif
                @if(!empty($data['institusi']))
                <tr>
                    <td>Institusi:</td>
                    <td>{{ $data['institusi'] }}</td>
                </tr>
                @endif
                <tr>
                    <td>Tanggal Request:</td>
                    <td>{{ $data['request_date'] }}</td>
                </tr>
            </table>
        </div>

        <div class="katalog-info">
            <h3>📚 Detail Katalog TA yang Diminta</h3>
            <table class="table-info">
                <tr>
                    <td>Nama KoTA:</td>
                    <td><strong>{{ $data['kota_nama'] }}</strong></td>
                </tr>
                <tr>
                    <td>Judul TA:</td>
                    <td>{{ $data['judul_ta'] }}</td>
                </tr>
                <tr>
                    <td>Periode:</td>
                    <td>{{ $data['periode'] }}</td>
                </tr>
                <tr>
                    <td>Kelas:</td>
                    <td>{{ $data['kelas'] }}</td>
                </tr>
            </table>
        </div>

        <!-- Info Anggota KoTA untuk Referensi Koordinator -->
        @if(!empty($data['mahasiswa_list']) || !empty($data['dosen_list']))
        <div class="anggota-info">
            <h3>👥 Referensi Anggota KoTA</h3>
            <small class="text-muted">Informasi anggota yang terlibat dalam TA ini (untuk referensi koordinator)</small>
            
            @if(!empty($data['mahasiswa_list']))
            <div style="margin-top: 10px;">
                <strong>Alumni (Mahasiswa):</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    @foreach($data['mahasiswa_list'] as $mhs)
                    <li>{{ $mhs['name'] }} ({{ $mhs['nim'] }}) - {{ $mhs['email'] }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            @if(!empty($data['dosen_list']))
            <div style="margin-top: 10px;">
                <strong>Dosen Pembimbing:</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    @foreach($data['dosen_list'] as $dsn)
                    <li>{{ $dsn['name'] }} - {{ $dsn['email'] }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @endif

        <div class="info-box">
            <h3>🎯 Tujuan Request</h3>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-style: italic; border-left: 4px solid #007bff;">
                "{{ $data['tujuan_request'] }}"
            </div>
        </div>

        @if(!empty($data['pesan']))
        <div class="info-box">
            <h3>💬 Pesan Tambahan dari Pemohon</h3>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-style: italic; border-left: 4px solid #28a745;">
                "{{ $data['pesan'] }}"
            </div>
        </div>
        @endif

        <hr style="margin: 30px 0; border: 1px solid #dee2e6;">

        <div class="action-buttons">
            <h3>⚡ Aksi yang Dapat Dilakukan</h3>
            <p>Sebagai Koordinator TA, Anda dapat:</p>
            
            <div style="margin: 20px 0;">
                @php
                    $approveSubject = "Re: Request Katalog TA - {$data['kota_nama']} [APPROVED]";
                    $approveBody = "Halo {$data['sender_name']},%0D%0A%0D%0ARequest Anda untuk akses katalog TA '{$data['kota_nama']}' telah disetujui.%0D%0A%0D%0ASilakan download file katalog melalui link berikut:%0D%0A[MASUKKAN LINK DOWNLOAD DI SINI]%0D%0A%0D%0AHormat kami,%0D%0AKoordinator TA Jurusan";
                    
                    $infoSubject = "Re: Request Katalog TA - {$data['kota_nama']} [INFO DIPERLUKAN]";
                    $infoBody = "Halo {$data['sender_name']},%0D%0A%0D%0ATerima kasih atas request katalog TA '{$data['kota_nama']}'.%0D%0A%0D%0AUntuk memproses request Anda, kami memerlukan informasi tambahan:%0D%0A1. [SEBUTKAN INFO YANG DIPERLUKAN]%0D%0A2. [SEBUTKAN INFO LAINNYA]%0D%0A%0D%0ASilakan balas email ini dengan informasi tersebut.%0D%0A%0D%0AHormat kami,%0D%0AKoordinator TA Jurusan";
                    
                    $rejectSubject = "Re: Request Katalog TA - {$data['kota_nama']} [DITOLAK]";
                    $rejectBody = "Halo {$data['sender_name']},%0D%0A%0D%0AMohon maaf, request Anda untuk akses katalog TA '{$data['kota_nama']}' tidak dapat kami setujui saat ini.%0D%0A%0D%0AAlasan: [SEBUTKAN ALASAN]%0D%0A%0D%0AAnda dapat menghubungi kami jika ada pertanyaan lebih lanjut.%0D%0A%0D%0AHormat kami,%0D%0AKoordinator TA Jurusan";
                @endphp
                
                <a href="mailto:{{ $data['sender_email'] }}?subject={{ urlencode($approveSubject) }}&body={{ $approveBody }}" class="btn btn-success">
                    ✅ Setujui & Kirim Link Download
                </a>
                
                <a href="mailto:{{ $data['sender_email'] }}?subject={{ urlencode($infoSubject) }}&body={{ $infoBody }}" class="btn">
                    ❓ Minta Info Tambahan
                </a>
                
                <a href="mailto:{{ $data['sender_email'] }}?subject={{ urlencode($rejectSubject) }}&body={{ $rejectBody }}" class="btn btn-danger">
                    ❌ Tolak Request
                </a>
            </div>
        </div>

        <div class="info-box">
            <h3>📋 Panduan untuk Koordinator TA</h3>
            <p><strong>Langkah-langkah Pemrosesan Request:</strong></p>
            <ol>
                <li><strong>Verifikasi Identitas:</strong> Pastikan pemohon adalah mahasiswa/dosen yang valid</li>
                <li><strong>Cek Kelengkapan Data:</strong> Pastikan tujuan request jelas dan sesuai kebijakan</li>
                <li><strong>Evaluasi Prioritas:</strong> 
                    <ul>
                        <li>🔴 Urgent: Respon dalam 24 jam</li>
                        <li>🟠 Tinggi: Respon dalam 3 hari</li>
                        <li>🟡 Sedang: Respon dalam 1 minggu</li>
                        <li>🟢 Rendah: Respon fleksibel</li>
                    </ul>
                </li>
                <li><strong>Koordinasi dengan Anggota:</strong> Jika perlu, hubungi alumni/dosen pembimbing untuk konfirmasi</li>
                <li><strong>File Sharing:</strong> Gunakan platform resmi untuk berbagi file (Drive institusi, dll)</li>
                <li><strong>Dokumentasi:</strong> Catat semua request dan response untuk audit</li>
            </ol>
        </div>

        <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; border-left: 5px solid #ffc107;">
            <h4>⚠️ Catatan Penting untuk Koordinator</h4>
            <ul>
                <li>✅ Pastikan file yang dibagikan tidak mengandung informasi pribadi/sensitif</li>
                <li>📋 Konfirmasi bahwa file sudah mendapat persetujuan untuk dibagikan</li>
                <li>🔒 Gunakan watermark atau pembatasan akses jika diperlukan</li>
                <li>📊 Request ID: <strong>{{ $data['request_id'] ?? 'N/A' }}</strong> (untuk tracking)</li>
                <li>🏛️ Sesuai kebijakan institusi tentang berbagi data akademik</li>
                <li>👥 Koordinator memiliki wewenang penuh untuk menyetujui/menolak request</li>
            </ul>
        </div>

        <div style="background-color: #e8f5e8; padding: 15px; border-radius: 8px; border-left: 5px solid #28a745;">
            <h4>💡 Tips untuk Koordinator</h4>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Berikan akses sesuai dengan tujuan yang disebutkan pemohon</li>
                <li>Untuk request penelitian, pertimbangkan memberikan abstrak atau ringkasan</li>
                <li>Untuk request metodologi, berikan panduan umum tanpa detail sensitif</li>
                <li>Gunakan sistem tracking untuk mencatat semua approval/rejection</li>
                <li>Koordinasi dengan bagian akademik jika ada kebijakan khusus</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p><strong>🏢 Sistem Katalog TA - Koordinator Panel</strong></p>
        <p>Email ini dikirim otomatis dari sistem. Untuk bantuan teknis, hubungi IT Support.</p>
        <p>&copy; {{ date('Y') }} Sistem Informasi Katalog TA</p>
    </div>
</body>
</html>