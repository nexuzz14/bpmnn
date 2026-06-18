<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Disposisi - {{ $disposisi->suratMasuk->nomor_surat }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 14px; font-weight: normal; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-decoration: underline; }
        table { w-full; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #000; padding: 8px; vertical-align: top; }
        .field-label { width: 150px; font-weight: bold; }
        .footer { margin-top: 50px; text-align: right; }
        .signature-box { display: inline-block; text-align: left; width: 250px; }
        .signature-name { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
        <h2>BIRO KEUANGAN DAN BARANG MILIK NEGARA</h2>
        <p style="margin: 5px 0 0; font-size: 12px;">Jl. Lapangan Banteng Barat No. 3-4 Jakarta 10710</p>
    </div>

    <div class="title">LEMBAR DISPOSISI</div>

    <table style="width: 100%;">
        <tr>
            <td class="field-label">Nomor Surat</td>
            <td>{{ $disposisi->suratMasuk->nomor_surat }}</td>
        </tr>
        <tr>
            <td class="field-label">Tanggal Surat</td>
            <td>{{ \Carbon\Carbon::parse($disposisi->suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="field-label">Asal Surat</td>
            <td>{{ $disposisi->suratMasuk->asal_surat }}</td>
        </tr>
        <tr>
            <td class="field-label">Perihal</td>
            <td>{{ $disposisi->suratMasuk->perihal }}</td>
        </tr>
        <tr>
            <td class="field-label">Diterima Tanggal</td>
            <td>{{ \Carbon\Carbon::parse($disposisi->suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td class="field-label" style="width: 50%;"><strong>DITERUSKAN KEPADA:</strong><br><br>{{ $disposisi->penerima->name }}<br>({{ $disposisi->penerima->jabatan }})</td>
            <td style="width: 50%;"><strong>INSTRUKSI / CATATAN:</strong><br><br>{{ $disposisi->instruksi }}</td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Tenggat Waktu:</strong> {{ \Carbon\Carbon::parse($disposisi->tenggat_waktu)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Jakarta, {{ \Carbon\Carbon::parse($disposisi->created_at)->translatedFormat('d F Y') }}</p>
            <p><strong>Pengirim Disposisi,</strong></p>
            <div class="signature-name">{{ $disposisi->pengirim->name }}</div>
            <div>{{ $disposisi->pengirim->jabatan }}</div>
        </div>
    </div>
</body>
</html>
