<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\AktivitasKuliahMahasiswa;
use App\Models\LulusDO;

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
                'status' => 404,
                'message' => 'Mahasiswa tidak ditemukan. Periksa Kembali NIM yang Anda masukkan.',
            ], 404);
        }

        return response()->json([
            'data' => $mahasiswa
        ]);
    }

    public function all_mahasiswa(Request $request)
    {
        $validated = $request->validate([
            'id_prodi'        => 'required|string',
            'angkatan'        => 'required|string',
            'nama_mahasiswa'  => 'required|string',
            // 'status_aktif'    => 'nullable|string',
        ]);

        $query = Mahasiswa::where('id_prodi', $validated['id_prodi'])
            ->whereRaw('LEFT(id_periode_masuk, 4) = ?', $validated['angkatan'])
            ->where('nama_mahasiswa', 'like', "%{$validated['nama_mahasiswa']}%");

        $mahasiswa = $query->orderBy('id_periode_masuk', 'desc')->get();

        // Mapping data agar struktur JSON rapi
        $result = $mahasiswa->map(function ($mhs) {
            return [
                'id_registrasi_mahasiswa' => $mhs->id_registrasi_mahasiswa,
                'nim' => $mhs->nim,
                'nama_mahasiswa' => $mhs->nama_mahasiswa,
                'angkatan' => substr($mhs->id_periode_masuk, 0, 4),
                'id_prodi' => $mhs->id_prodi,
                'nama_program_studi' => $mhs->nama_program_studi,
                'status_mahasiswa' => $mhs->keterangan_keluar ?? 'Aktif',
            ];
        });

        if ($result->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Tidak ada data mahasiswa sesuai filter yang diberikan.'
            ], 404);
        }

        return response()->json([
            'totalData' => $result->count(),
            'data' => $result
        ], 200);
    }

    public function mahasiswa_by_id_reg(Request $request)
    {
        $validated = $request->validate([
            'id_registrasi_mahasiswa' => 'required|string',
        ]);

        // Ambil data mahasiswa saja (tanpa eager load besar)
        $mahasiswa = Mahasiswa::select('id_registrasi_mahasiswa', 'nim', 'nama_mahasiswa', 'id_prodi', 'id_periode_masuk')
            // ->with(['prodi:id_prodi,nama_program_studi,nama_jenjang_pendidikan,status'])
            ->where('id_registrasi_mahasiswa', $validated['id_registrasi_mahasiswa'])
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'status' => 404,
                'message' => 'Mahasiswa tidak ditemukan. Periksa Kembali id_registrasi_mahasiswa yang Anda masukkan.'
            ], 404);
        }

        // Susun hasil JSON rapi
        $result = [
            'id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa,
            'nim' => $mahasiswa->nim,
            'nama_mahasiswa' => $mahasiswa->nama_mahasiswa,
            'angkatan' => substr($mahasiswa->id_periode_masuk, 0, 4),
            // 'status_mahasiswa' => $lulusDo->nama_jenis_keluar ?? 'Aktif',
            // 'tanggal_keluar' => $lulusDo->tanggal_keluar ?? '-',
            // 'no_seri_ijazah' => $lulusDo->no_seri_ijazah ?? '-',

            // ✅ Nested object "prodi"
            'prodi' => [
                'id_prodi' => $mahasiswa->id_prodi,
                'nama_program_studi' => $mahasiswa->nama_program_studi,
            ],
        ];


        return response()->json([
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $result
        ], 200);
    }


    public function akm_by_nim(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
        ]);

        // Ambil data mahasiswa saja (tanpa eager load besar)
        $akm = AktivitasKuliahMahasiswa::select('id_registrasi_mahasiswa', 'nim', 'nama_mahasiswa', 'id_semester', 'sks_semester', 'ips', 'ipk')
            // ->with(['prodi:id_prodi,nama_program_studi,nama_jenjang_pendidikan,status'])
            ->where('nim', $validated['nim'])
            ->orderBy('id_semester', 'ASC')
            ->get();

        if (!$akm) {
            return response()->json([
                'status' => 404,
                'message' => 'Data AKM tidak ditemukan. Periksa Kembali NIM yang Anda masukkan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data AKM mahasiswa berhasil diambil.',
            'data' => $akm
        ], 200);
    }

    public function lulus_do_nim(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
        ]);

        $nim = $validated['nim'];

        $lulusDo = DB::connection('pdunsri')->table('list_mahasiswa_lulus_do as lulus_do')
                // ->leftJoin('program_studi as prodi', 'riwayat.id_prodi', '=', 'prodi.id_prodi')
                ->where('lulus_do.nim', $nim)
                ->select(
                'lulus_do.nim as nim',
                'lulus_do.nama_mahasiswa as nama_mahasiswa',
                'lulus_do.id_prodi as id_prodi',
                'lulus_do.nama_program_studi as nama_program_studi',
                'lulus_do.id_prodi as angkatan',
                'lulus_do.nama_jenis_keluar as status_mahasiswa',
                'lulus_do.tanggal_keluar',
                'lulus_do.no_seri_ijazah'
                )
                ->first();

        if (!$lulusDo) {
            return response()->json([
                'status' => 404,
                'message' => 'Data mahasiswa tidak ditemukan. Pastikan mahasiswa bukan berstatus Aktif dan periksa kembali NIM yang Anda masukkan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $lulusDo
        ], 200);
    }

}
