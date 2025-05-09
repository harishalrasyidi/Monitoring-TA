{{-- resources/views/emails/request_katalog.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Katalog TA</title>
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
            background-color: #3490dc;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
        .info-block {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3490dc;
        }
        .btn {
            display: inline-block;
            background-color: #3490dc;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .quote {
            background-color: #f7f7f9;
            padding: 15px;
            border-left: 4px solid #aaa;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Request Katalog Tugas Akhir</h2>
    </div>
    
    <div class="content">
        <p>Halo <strong>{{ explode(' ', $data['sender_name'])[0] }}</strong>,</p>
        
        <p>Seseorang telah membuat permintaan untuk katalog Tugas Akhir (TA) dengan informasi berikut:</p>
        
        <div class="info-block">
            <p><strong>Judul TA:</strong> {{ $data['judul_ta'] }}</p>
            <p><strong>Kota:</strong> {{ $data['kota_nama'] }}</p>
            <p><strong>Periode:</strong> {{ $data['periode'] }}</p>
            <p><strong>Kelas:</strong> {{ $data['kelas'] }}</p>
        </div>
        
        <p><strong>Detail Permintaan:</strong></p>
        <div class="quote">
            {{ $data['tujuan_request'] }}
        </div>
        
        <p><strong>Informasi Pemohon:</strong></p>
        <ul>
            <li>Nama: {{ $data['sender_name'] }}</li>
            <li>Email: <a href="mailto:{{ $data['sender_email'] }}">{{ $data['sender_email'] }}</a></li>
        </ul>
        
        <p>Jika Anda bersedia berbagi katalog TA dengan pemohon, silakan balas email langsung ke alamat di atas dengan melampirkan file yang diminta.</p>
        
        <p>Terima kasih atas perhatian dan bantuannya.</p>
        
        <p>Salam,<br>
        Tim Katalog TA</p>
    </div>
    
    <div class="footer">
        <p>Email ini dibuat otomatis. Harap tidak membalas ke email ini.</p>
        <p>&copy; {{ date('Y') }} Sistem Katalog TA</p>
    </div>
</body>
</html>