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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
        }
        .info-box {
            background-color: white;
            padding: 20px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .contact-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>🎓 Request Katalog KoTA</h2>
        <p>Permintaan Akses Informasi Tugas Akhir</p>
    </div>

    <div class="content">
        <p>Halo,</p>
        
        <p>Anda menerima permintaan akses katalog KoTA dari mahasiswa berikut:</p>

        <div class="contact-info">
            <strong>📧 Informasi Pemohon:</strong><br>
            <strong>Nama:</strong> {{ $data['sender_name'] }}<br>
            <strong>Email:</strong> {{ $data['sender_email'] }}<br>
            <strong>NIM:</strong> {{ $data['sender_nim'] }}<br>
            <strong>Tanggal Request:</strong> {{ $data['request_date'] }}
        </div>

        <div class="info-box">
            <strong>📚 Detail KoTA yang Diminta:</strong><br>
            <strong>Nama KoTA:</strong> {{ $data['kota_nama'] }}<br>
            <strong>Judul TA:</strong> {{ $data['judul_ta'] }}<br>
            <strong>Periode:</strong> {{ $data['periode'] }}<br>
            <strong>Kelas:</strong> {{ $data['kelas'] }}
        </div>

        <div class="info-box">
            <strong>🎯 Tujuan Request:</strong><br>
            <p style="font-style: italic; margin: 10px 0;">
                "{{ $data['tujuan_request'] }}"
            </p>
        </div>

        @if(!empty($data['pesan']))
        <div class="info-box">
            <strong>💬 Pesan Tambahan:</strong><br>
            <p style="font-style: italic; margin: 10px 0;">
                "{{ $data['pesan'] }}"
            </p>
        </div>
        @endif

        <hr style="margin: 30px 0;">

        <h3>🤝 Bagaimana Cara Merespons?</h3>
        <p>Jika Anda bersedia berbagi informasi katalog TA ini, silakan hubungi pemohon langsung melalui:</p>
        
        <div style="text-align: center; margin: 20px 0;">
            <a href="mailto:{{ $data['sender_email'] }}?subject=Re: Request Katalog KoTA - {{ $data['kota_nama'] }}" class="btn">
                📧 Balas Email Ini
            </a>
        </div>

        <p><strong>Catatan Penting:</strong></p>
        <ul>
            <li>Anda tidak wajib untuk merespons atau berbagi informasi</li>
            <li>Jika berkenan berbagi, pastikan tidak ada informasi sensitif</li>
            <li>Anda bisa berbagi dokumen yang sudah dipublikasikan atau diizinkan</li>
            <li>Pertimbangkan untuk berdiskusi terlebih dahulu sebelum berbagi file</li>
        </ul>
    </div>

    <div class="footer">
        <p>Email ini dikirim otomatis. Harap tidak membalas ke email ini.</p>
        <p>Jika ada pertanyaan, silakan hubungi administrator sistem.</p>
        <p>&copy; {{ date('Y') }} Sistem Katalog TA</p>
    </div>
</body>
</html>