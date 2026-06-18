<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Persuratan</title>
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
        <h1>{{ $title ?? 'Biro Keuangan & BMN Kemenag RI' }}</h1>
        <p>{{ $subtitle ?? 'Rekapitulasi Persuratan' }} - {{ request()->query('tahun', now()->year) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Periode</th>
                @if(isset($details[0]['disposisi_masuk']))
                    <th>Disposisi Masuk</th>
                    <th>Draft Direview</th>
                    <th>Disetujui</th>
                    <th>Dikembalikan</th>
                    <th>Waktu Review</th>
                @elseif(isset($details[0]['surat_keluar']))
                    <th>Surat Masuk</th>
                    <th>Surat Keluar</th>
                    <th>Sedang Diproses</th>
                    <th>Selesai</th>
                @elseif(isset($details[0]['disetujui']))
                    <th>Surat Masuk</th>
                    <th>Disetujui</th>
                    <th>Dikembalikan</th>
                    <th>Rata-Rata Waktu Review</th>
                @else
                    <th>Surat Masuk</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail['periode'] }}</td>
                @if(isset($detail['disposisi_masuk']))
                    <td>{{ $detail['disposisi_masuk'] }}</td>
                    <td>{{ $detail['draft_direview'] }}</td>
                    <td>{{ $detail['disetujui'] }}</td>
                    <td>{{ $detail['dikembalikan'] }}</td>
                    <td>{{ $detail['waktu_review'] }}</td>
                @elseif(isset($detail['surat_keluar']))
                    <td>{{ $detail['surat_masuk'] ?? 0 }}</td>
                    <td>{{ $detail['surat_keluar'] }}</td>
                    <td>{{ $detail['sedang_diproses'] }}</td>
                    <td>{{ $detail['selesai'] }}</td>
                @elseif(isset($detail['disetujui']))
                    <td>{{ $detail['surat_masuk'] ?? 0 }}</td>
                    <td>{{ $detail['disetujui'] }}</td>
                    <td>{{ $detail['dikembalikan'] }}</td>
                    <td>{{ $detail['waktu_review'] }}</td>
                @else
                    <td>{{ $detail['surat_masuk'] ?? 0 }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;">Tidak ada data rekapitulasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }} | SiPersurat
    </div>
</body>
</html>
