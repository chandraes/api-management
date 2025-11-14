<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LulusDO extends Model
{
    protected $connection = 'pdunsri';
    protected $table = 'list_mahasiswa_lulus_do';
    protected $primaryKey = 'id_registrasi_mahasiswa';
    public $incrementing = false;
    public $timestamps = false;
}

