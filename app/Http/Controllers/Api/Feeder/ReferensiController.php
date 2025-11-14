<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferensiController extends Controller
{
    public function prodi(Request $request)
    {
        $validated = $request->validate([
            'id_prodi' => 'nullable|string',
            'nama_jenjang_pendidikan' => 'nullable|string',
            'id_jenjang_pendidikan' => 'nullable|integer',
        ]);

        $id_prodi = isset($validated['id_prodi']) ? $validated['id_prodi'] : null;
        $jenjang = isset($validated['nama_jenjang_pendidikan']) ? $validated['nama_jenjang_pendidikan'] : null;
        $id_jenjang = isset($validated['id_jenjang_pendidikan']) ? $validated['id_jenjang_pendidikan'] : null;

        // Ambil data program studi dari database
        $prodi = DB::connection('pdunsri')->table('program_studi')
            ->when($id_prodi, function ($query) use ($id_prodi) {
                return $query->where('id_prodi', $id_prodi);
            })
            ->when($jenjang, function ($query) use ($jenjang) {
                return $query->where('nama_jenjang_pendidikan', $jenjang);
            })
            ->when($id_jenjang, function ($query) use ($id_jenjang) {
                return $query->where('id_jenjang_pendidikan', $id_jenjang);
            })
            ->select(
                'id_prodi',
                'nama_program_studi',
                'id_jenjang_pendidikan',
                'nama_jenjang_pendidikan',
                'status'
            )
            ->get();

        if ($prodi->isEmpty()) {
            return response()->json([
                'message' => 'Program studi tidak ditemukan. Periksa Kembali data yang anda masukan.',
            ], 404);
        }

        return response()->json([
            'data' => $prodi
        ]);
    }

    public function get_prodi(Request $request)
    {
        // ✅ Ambil seluruh data program studi dari koneksi `pdunsri`
        $prodi = DB::connection('pdunsri')
            ->table('program_studi')
            ->select(
                'id_prodi',
                // 'kode_program_studi',
                // 'id_jenjang_pendidikan',
                'nama_jenjang_pendidikan',
                'nama_program_studi',
                // 'status'
            )
            ->orderBy('nama_jenjang_pendidikan', 'asc')
            ->orderBy('nama_program_studi', 'asc')
            ->get();

        // ✅ Jika tidak ditemukan
        if ($prodi->isEmpty()) {
            return response()->json([
                'message' => 'Program studi tidak ditemukan.',
                'totalData' => 0,
                'totalRow'  => 0,
                'data'      => []
            ], 404);
        }

        // ✅ Hitung jumlah kolom dari record pertama
        $totalRow = count((array)$prodi->first());

        // ✅ Jika ditemukan
        return response()->json([
            'totalData' => $prodi->count(), // jumlah baris data
            'totalRow'  => $totalRow,       // jumlah kolom (field)
            'data'      => $prodi
        ], 200);
    }




    public function informasi_prodi($id_prodi)
{
    $prodi = DB::connection('pdunsri')
        ->table('program_studi')
        ->where('id_prodi', $id_prodi)
        ->select(
            'id_prodi',
            'kode_program_studi',
            'id_jenjang_pendidikan',
            'nama_jenjang_pendidikan',
            'nama_program_studi',
            'status'
        )
        ->first();

    if (!$prodi) {
        return response()->json([
            'message' => 'Program studi tidak ditemukan.',
            'id_prodi' => $id_prodi
        ], 404);
    }

    return response()->json([
        'message' => 'Data program studi berhasil diambil.',
        'data' => $prodi
    ], 200);
}

    

    
}
