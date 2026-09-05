<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SamperinJenisBerkas extends Model
{
    protected $table = 'samperin_jenis_berkas';

    protected $primaryKey = 'jenis_berkas_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'jenis_berkas_uid',
        'jenis_berkas_kategori_id',
        'jenis_berkas_kode',
        'jenis_berkas_nama',
        'jenis_berkas_sifat',
        'jenis_berkas_format',
        'jenis_berkas_maksimal_mb',
        'jenis_berkas_keterangan',
        'jenis_berkas_status',
        'jenis_berkas_created_at',
        'jenis_berkas_updated_at',
    ];

    protected $casts = [
        'jenis_berkas_maksimal_mb' => 'integer',
        'jenis_berkas_status' => 'boolean',
        'jenis_berkas_created_at' => 'datetime',
        'jenis_berkas_updated_at' => 'datetime',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(
            SamperinBerkasKategori::class,
            'jenis_berkas_kategori_id',
            'kategori_id'
        );
    }

    public function permintaan(): HasMany
    {
        return $this->hasMany(
            SamperinPermintaanBerkas::class,
            'permintaan_jenis_berkas_id',
            'jenis_berkas_id'
        );
    }
}