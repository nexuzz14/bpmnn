<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arsip Surat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Biro Keuangan & BMN Kemenag RI</h1>
        <p>Laporan Arsip Surat Terpadu</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Agenda</th>
                <th>Nomor Surat</th>
                <th>Perihal</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Lokasi Fisik</th>
            </tr>
        </thead>
        <tbody>
            @foreach($arsips as $index => $arsip)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $arsip->nomor_agenda ?? '-' }}</td>
                <td>{{ $arsip->nomor_surat ?? '-' }}</td>
                <td>{{ $arsip->perihal }}</td>
                <td>{{ $arsip->jenis }}</td>
                <td>{{ $arsip->kategori }}</td>
                <td>{{ $arsip->created_at->format('d M Y') }}</td>
                <td>{{ $arsip->lokasi_fisik }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }} | SiPersurat
    </div>
</body>
</html>
