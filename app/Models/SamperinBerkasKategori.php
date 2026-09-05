<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SamperinBerkasKategori extends Model
{
    protected $table = 'samperin_berkas_kategori';

    protected $primaryKey = 'kategori_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'kategori_uid',
        'kategori_kode',
        'kategori_nama',
        'kategori_keterangan',
        'kategori_status',
        'kategori_created_at',
        'kategori_updated_at',
    ];

    protected $casts = [
        'kategori_status' => 'boolean',
        'kategori_created_at' => 'datetime',
        'kategori_updated_at' => 'datetime',
    ];

    public function jenisBerkas(): HasMany
    {
        return $this->hasMany(
            SamperinJenisBerkas::class,
            'jenis_berkas_kategori_id',
            'kategori_id'
        );
    }
}