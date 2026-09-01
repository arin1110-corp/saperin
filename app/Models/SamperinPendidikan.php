<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinPendidikan extends Model
{
    protected $table = 'samperin_pendidikan';

    protected $primaryKey = 'pendidikan_id';

    public $timestamps = false;

    protected $fillable = ['pendidikan_uid', 'pendidikan_kode', 'pendidikan_jenjang', 'pendidikan_jurusan', 'pendidikan_status'];

    protected $casts = [
        'pendidikan_id' => 'integer',
        'pendidikan_status' => 'integer',
        'pendidikan_created_at' => 'datetime',
        'pendidikan_updated_at' => 'datetime',
    ];
}