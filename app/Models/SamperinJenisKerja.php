<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinJenisKerja extends Model
{
    protected $table = 'samperin_jenis_kerja';

    protected $primaryKey = 'jenis_kerja_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['jenis_kerja_uid', 'jenis_kerja_kode', 'jenis_kerja_nama', 'jenis_kerja_status'];

    protected $casts = [
        'jenis_kerja_status' => 'integer',
    ];
}