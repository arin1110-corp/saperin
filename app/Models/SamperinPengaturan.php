<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinPengaturan extends Model
{
    protected $table = 'samperin_pengaturan';

    protected $primaryKey = 'pengaturan_id';

    public $incrementing = true;

    protected $keyType = 'int';

    const CREATED_AT = 'pengaturan_created_at';

    const UPDATED_AT = 'pengaturan_updated_at';

    protected $fillable = ['pengaturan_id', 'pengaturan_uid', 'pengaturan_kode', 'pengaturan_nama', 'pengaturan_nilai', 'pengaturan_tipe', 'pengaturan_keterangan', 'pengaturan_status'];

    protected $casts = [
        'pengaturan_id' => 'integer',
        'pengaturan_status' => 'boolean',
    ];
}