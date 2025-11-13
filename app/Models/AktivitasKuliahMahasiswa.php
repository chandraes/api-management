<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasKuliahMahasiswa extends Model
{
    protected $connection = 'pdunsri';
    protected $table = 'aktivitas_kuliah_mahasiswa';
    protected $primaryKey = 'id_aktivitas';
    public $incrementing = false;
    public $timestamps = false;
}

