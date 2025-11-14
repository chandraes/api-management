<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $connection = 'pdunsri';
    protected $table = 'list_riwayat_pendidikan_mahasiswa';
    protected $primaryKey = 'id_registrasi_mahasiswa';
    public $incrementing = false;
    public $timestamps = false;

    // Relasi ke Program Studi
    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    // Relasi ke data keluar/lulus/DO
    public function lulusDo()
    {
        return $this->hasOne(LulusDO::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    // Relasi ke aktivitas kuliah mahasiswa (akm)
    public function akm()
    {
        return $this->hasMany(AktivitasKuliahMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }
}
