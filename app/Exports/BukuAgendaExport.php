<?php

namespace App\Exports;

use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BukuAgendaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return SuratMasuk::whereMonth('tanggal_terima', $this->bulan)
            ->whereYear('tanggal_terima', $this->tahun)
            ->orderBy('tanggal_terima', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Surat',
            'Tanggal Surat',
            'Tanggal Terima',
            'Asal Surat',
            'Perihal',
            'Status',
        ];
    }

    public function map($surat): array
    {
        static $no = 1;
        return [
            $no++,
            $surat->nomor_surat,
            $surat->tanggal_surat,
            $surat->tanggal_terima,
            $surat->asal_surat,
            $surat->perihal,
            $surat->status,
        ];
    }
}
