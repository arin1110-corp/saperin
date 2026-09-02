<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinFolder extends Model
{
    protected $table = 'samperin_folder';

    protected $primaryKey = 'folder_id';

    public $incrementing = true;

    protected $keyType = 'int';

    const CREATED_AT = 'folder_created_at';

    const UPDATED_AT = 'folder_updated_at';

    protected $fillable = ['folder_id', 'folder_uid', 'folder_kode', 'folder_nama', 'folder_jenis', 'folder_jenis_kerja_id', 'folder_prefix', 'folder_drive_id', 'folder_keterangan', 'folder_status'];

    protected $casts = [
        'folder_id' => 'integer',
        'folder_jenis_kerja_id' => 'integer',
        'folder_status' => 'boolean',
    ];
}