<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
         $validated = $request->validate([
            'nim' => 'required|string',
        ]);

        $nim = $validated['nim'];

        // Ambil data mahasiswa dari database
        $mahasiswa = DB::connection('pdunsri')->table('list_riwayat_pendidikan_mahasiswa as riwayat')
                ->leftJoin('program_studi as prodi', 'riwayat.id_prodi', '=', 'prodi.id_prodi')
                ->where('riwayat.nim', $nim)
                ->select(
                'riwayat.nim as nim',
                'riwayat.nama_mahasiswa as nama_mahasiswa',
                'riwayat.id_prodi as id_prodi',
                'prodi.nama_program_studi as nama_program_studi',
                'prodi.nama_jenjang_pendidikan as nama_jenjang_pendidikan',
                DB::raw('LEFT(riwayat.id_periode_masuk, 4) as angkatan'),
                DB::raw("COALESCE(riwayat.keterangan_keluar, 'Aktif') as status_mahasiswa")
                )
            ->orderBy('riwayat.id_periode_masuk', 'desc')
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Mahasiswa tidak ditemukan. Periksa Kembali NIM yang Anda masukkan.',
            ], 404);
        }

        return response()->json([
            'data' => $mahasiswa
        ]);
    }
}
