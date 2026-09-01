<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinGolongan extends Model
{
    protected $table = 'samperin_golongan';

    protected $primaryKey = 'golongan_id';

    public $timestamps = false;

    protected $fillable = ['golongan_uid', 'golongan_kode', 'golongan_nama', 'golongan_pangkat', 'golongan_status'];

    protected $casts = [
        'golongan_id' => 'integer',
        'golongan_status' => 'integer',
        'golongan_created_at' => 'datetime',
        'golongan_updated_at' => 'datetime',
    ];
}