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
        // with([
        //     // 'prodi:id_prodi,nama_program_studi,nama_jenjang_pendidikan',
        //     'lulusDo:id_registrasi_mahasiswa,nama_jenis_keluar,tanggal_keluar,no_seri_ijazah',
        //     // 'akm:id_registrasi_mahasiswa,id_semester,ips,ipk,sks_total,sks_semester,nama_status_mahasiswa'
        // ])
        //     ->
            
        // if (!empty($validated['nama_mahasiswa'])) {
        //     $query->where('nama_mahasiswa', 'like', "%{$validated['nama_mahasiswa']}%");
        // }

        // if (!empty($validated['status_aktif'])) {
        //     if (strtolower($validated['status_aktif']) === 'aktif') {
        //         $query->whereDoesntHave('lulusDo');
        //     } else {
        //         $query->whereHas('lulusDo', function ($q) use ($validated) {
        //             $q->where('nama_jenis_keluar', 'like', "%{$validated['status_aktif']}%");
        //         });
        //     }
        // }

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
                // 'tanggal_keluar' => $mhs->lulusDo->tanggal_keluar ?? '-',
                // 'no_seri_ijazah' => $mhs->lulusDo->no_seri_ijazah ?? '-',
                // 'akm' => $mhs->akm->map(function ($a) {
                //     return [
                //         'id_semester' => $a->id_semester,
                //         'ips' => $a->ips,
                //         'ipk' => $a->ipk,
                //         'sks_total' => $a->sks_total,
                //         'sks_semester' => $a->sks_semester,
                //         'nama_status_mahasiswa' => $a->nama_status_mahasiswa,
                //     ];
                // }),
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

        // Lazy load relasi kecil (1 record)
        // $lulusDo = $mahasiswa->lulusDo()
        //     ->select('id_registrasi_mahasiswa', 'nama_jenis_keluar', 'tanggal_keluar', 'no_seri_ijazah')
        //     ->first();

        // Lazy load relasi besar (dibatasi)
        // $akm = $mahasiswa->akm()
        //     ->select('id_registrasi_mahasiswa', 'id_semester', 'ips', 'ipk', 'sks_total', 'sks_semester', 'nama_status_mahasiswa')
        //     ->orderByDesc('id_semester')
        //     ->limit(20)
        //     ->get();

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

            // ✅ Nested list "akm"
            // 'akm' => $akm->map(function ($a) {
            //     return [
            //         'id_semester' => $a->id_semester,
            //         'ips' => $a->ips,
            //         'ipk' => $a->ipk,
            //         'sks_total' => $a->sks_total,
            //         'sks_semester' => $a->sks_semester,
            //         'nama_status_mahasiswa' => $a->nama_status_mahasiswa,
            //     ];
            // }),
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

    public function mahasiswa_lulus_do(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
        ]);

        // Ambil data mahasiswa saja (tanpa eager load besar)
        $lulusDo = LulusDO::select('id_registrasi_mahasiswa', 'nim', 'nama_mahasiswa', 'id_prodi', 'angkatan', 
                                    'nama_jenis_keluar', 'tanggal_keluar', 'no_seri_ijazah', 'id_prodi', 'nama_program_studi')
                ->where('nim', $validated['nim'])
                ->first();

        if (!$lulusDo) {
            return response()->json([
                'status' => 404,
                'message' => 'Data mahasiswa tidak ditemukan. Pastikan mahasiswa bukan berstatus Aktif dan periksa kembali NIM yang Anda masukkan.'
            ], 404);
        }

        // Susun hasil JSON rapi
        $result = [
            'id_registrasi_mahasiswa' => $lulusDo->id_registrasi_mahasiswa,
            'nim' => $lulusDo->nim,
            'nama_mahasiswa' => $lulusDo->nama_mahasiswa,
            'angkatan' => $lulusDo->angkatan,
            'status_mahasiswa' => $lulusDo->nama_jenis_keluar,
            'tanggal_keluar' => $lulusDo->tanggal_keluar ?? '-',
            'no_seri_ijazah' => $lulusDo->no_seri_ijazah ?? '-',

            // ✅ Nested object "prodi"
            'prodi' => [
                'id_prodi' => $lulusDo->id_prodi,
                'nama_program_studi' => $lulusDo->nama_program_studi,
            ],
        ];

        return response()->json([
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $result
        ], 200);
    }

}
