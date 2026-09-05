<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SamperinPermintaanTarget extends Model
{
    protected $table = 'samperin_permintaan_target';

    protected $primaryKey = 'target_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['target_uid', 'target_permintaan_id', 'target_tipe', 'target_jenis_kerja_id', 'target_folder_id', 'target_status', 'target_created_at', 'target_updated_at'];

    protected $casts = [
        'target_status' => 'boolean',
        'target_created_at' => 'datetime',
        'target_updated_at' => 'datetime',
    ];

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(SamperinPermintaanBerkas::class, 'target_permintaan_id', 'permintaan_id');
    }

    public function jenisKerja(): BelongsTo
    {
        return $this->belongsTo(SamperinJenisKerja::class, 'target_jenis_kerja_id', 'jenis_kerja_id');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(SamperinFolder::class, 'target_folder_id', 'folder_id');
    }
}