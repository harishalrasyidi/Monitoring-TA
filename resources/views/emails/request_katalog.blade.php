<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Katalog KoTA</title>
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
            margin: 10px 0;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 Request Katalog KoTA</h1>
        <p>Permintaan Akses Informasi Tugas Akhir</p>
        <p><small>{{ $data['request_date'] }}</small></p>
        @if(!empty($data['request_id']))
        <p><small>ID Request: {{ $data['request_id'] }}</small></p>
        @endif
    </div>

    <div class="content">
        <p><strong>Halo Anggota KoTA,</strong></p>
        
        <p>Anda menerima permintaan akses katalog KoTA berikut:</p>

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
            <h3>📚 Detail KoTA yang Diminta</h3>
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

        <h3>🤝 Bagaimana Cara Merespons?</h3>
        <p>Jika Anda bersedia berbagi informasi katalog TA ini, silakan hubungi pemohon langsung melalui:</p>
        
        <div style="text-align: center; margin: 20px 0;">
            @php
                $subjectLine = "Re: Request Katalog KoTA - {$data['kota_nama']}";
                if (!empty($data['request_id'])) {
                    $subjectLine .= " (ID: {$data['request_id']})";
                }
                
                $emailBody = "Halo {$data['sender_name']},%0D%0A%0D%0A";
                $emailBody .= "Terima kasih atas request Anda untuk akses katalog KoTA '{$data['kota_nama']}'.%0D%0A%0D%0A";
                $emailBody .= "Saya bersedia membantu dengan kebutuhan Anda terkait: {$data['tujuan_request']}.%0D%0A%0D%0A";
                $emailBody .= "[SILAKAN TULIS RESPON ANDA DI SINI]%0D%0A%0D%0A";
                $emailBody .= "Hormat kami,%0D%0AAnggota KoTA {$data['kota_nama']}";
            @endphp
            
            <a href="mailto:{{ $data['sender_email'] }}?subject={{ urlencode($subjectLine) }}&body={{ $emailBody }}" class="btn">
                📧 Balas Email Ini
            </a>
        </div>

        <div class="info-box">
            <h3>📋 Panduan Respon</h3>
            <p><strong>Catatan Penting:</strong></p>
            <ul>
                <li>✅ Anda tidak wajib untuk merespons atau berbagi informasi</li>
                <li>🔒 Jika berkenan berbagi, pastikan tidak ada informasi sensitif atau pribadi</li>
                <li>📁 Anda bisa berbagi dokumen yang sudah dipublikasikan atau diizinkan untuk dibagi</li>
                <li>💬 Pertimbangkan untuk berdiskusi terlebih dahulu sebelum berbagi file</li>
                @if(!empty($data['deadline_request']))
                <li>⏰ Perhatikan deadline pemohon: <strong>{{ $data['deadline_request'] }}</strong></li>
                @endif
                <li>📋 Referensi ID Request: <strong>{{ $data['request_id'] ?? 'N/A' }}</strong></li>
            </ul>
        </div>

        <div style="background-color: #e8f5e8; padding: 15px; border-radius: 8px; border-left: 5px solid #28a745;">
            <h4>💡 Tips Berbagi Informasi</h4>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Bagikan abstrak atau ringkasan penelitian</li>
                <li>Berikan referensi literatur yang digunakan</li>
                <li>Jelaskan metodologi secara umum</li>
                <li>Diskusikan pembelajaran yang diperoleh</li>
                <li>Gunakan platform berbagi yang aman (Google Drive, dll)</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p><strong>🏢 Sistem Katalog TA</strong></p>
        <p>Email ini dikirim otomatis dari sistem. Harap tidak membalas ke alamat ini.</p>
        <p>Jika ada pertanyaan teknis, silakan hubungi administrator sistem.</p>
        <p>&copy; {{ date('Y') }} Sistem Informasi Katalog TA</p>
    </div>
</body>
</html>