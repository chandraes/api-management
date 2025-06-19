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
}
