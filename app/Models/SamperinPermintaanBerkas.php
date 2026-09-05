<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SamperinPermintaanTarget;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SamperinPermintaanBerkas extends Model
{
    protected $table = 'samperin_permintaan_berkas';

    protected $primaryKey = 'permintaan_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'permintaan_uid',
        'permintaan_jenis_berkas_id',
        'permintaan_tahun',
        'permintaan_periode',
        'permintaan_judul',
        'permintaan_tombol',
        'permintaan_keterangan',
        'permintaan_mulai',
        'permintaan_expired',
        'permintaan_status',
        'permintaan_created_at',
        'permintaan_updated_at',
    ];

    protected $casts = [
        'permintaan_tahun' => 'integer',
        'permintaan_mulai' => 'datetime',
        'permintaan_expired' => 'datetime',
        'permintaan_status' => 'boolean',
        'permintaan_created_at' => 'datetime',
        'permintaan_updated_at' => 'datetime',
    ];

    public function jenisBerkas(): BelongsTo
    {
        return $this->belongsTo(
            SamperinJenisBerkas::class,
            'permintaan_jenis_berkas_id',
            'jenis_berkas_id'
        );
    }

    public function target(): HasMany
    {
        return $this->hasMany(
            SamperinPermintaanTarget::class,
            'target_permintaan_id',
            'permintaan_id'
        );
    }

    public function pengumpulan(): HasMany
    {
        return $this->hasMany(
            SamperinPengumpulanBerkas::class,
            'pengumpulan_berkas_permintaan_id',
            'permintaan_id'
        );
    }
}