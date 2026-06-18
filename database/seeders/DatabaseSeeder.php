<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\Disposisi;
use App\Models\DrafSurat;
use App\Models\ReviuSurat;
use App\Models\SuratFinal;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. UNIT KERJA
        // ==========================================
        $unitKerjas = [
            ['id' => 1, 'nama' => 'Biro Keuangan & BMN', 'kode' => 'BKB', 'parent_id' => null, 'level' => 'biro'],
            ['id' => 2, 'nama' => 'Subbagian Tata Usaha Biro', 'kode' => 'TU', 'parent_id' => 1, 'level' => 'bagian'],
            ['id' => 3, 'nama' => 'Bagian Pelaksanaan Anggaran', 'kode' => 'BPA', 'parent_id' => 1, 'level' => 'bagian'],
            ['id' => 4, 'nama' => 'Sub Tim Perbendaharaan', 'kode' => 'STP', 'parent_id' => 3, 'level' => 'sub_tim'],
        ];

        foreach ($unitKerjas as $unitData) {
            UnitKerja::create($unitData);
        }

        // ==========================================
        // 2. USERS
        // ==========================================
        $usersData = [
            ['id' => 1, 'name' => 'Budi Santoso', 'email' => 'admin@bkbmn.go.id', 'role' => 'admin', 'jabatan' => 'Admin Persuratan', 'unit_kerja_id' => 1],
            ['id' => 2, 'name' => 'Ahmad Riza', 'email' => 'tu@bkbmn.go.id', 'role' => 'tata_usaha', 'jabatan' => 'Staf Tata Usaha', 'unit_kerja_id' => 2],
            ['id' => 3, 'name' => 'Siti Nurhaliza', 'email' => 'kabag@bkbmn.go.id', 'role' => 'kepala_bagian', 'jabatan' => 'Kepala Bagian Keuangan', 'unit_kerja_id' => 3],
            ['id' => 4, 'name' => 'Rizki Maulana', 'email' => 'kasubtim@bkbmn.go.id', 'role' => 'kepala_sub_tim', 'jabatan' => 'Kepala Sub Tim', 'unit_kerja_id' => 4],
            ['id' => 5, 'name' => 'Andi Wijaya', 'email' => 'staf@bkbmn.go.id', 'role' => 'staf', 'jabatan' => 'Staf Pelaksana', 'unit_kerja_id' => 4],
            ['id' => 6, 'name' => 'Dr. Hendra Gunawan', 'email' => 'kabiro@bkbmn.go.id', 'role' => 'kepala_biro', 'jabatan' => 'Kepala Biro', 'unit_kerja_id' => 1],
        ];

        $users = [];
        foreach ($usersData as $data) {
            $data['password'] = Hash::make('password');
            $data['email_verified_at'] = now();
            $users[$data['role']] = User::create($data);
        }

    }
}


