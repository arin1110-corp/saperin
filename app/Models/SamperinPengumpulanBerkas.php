<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SamperinPengumpulanBerkas extends Model
{
    protected $table = 'samperin_pengumpulan_berkas';

    protected $primaryKey = 'pengumpulan_berkas_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'pengumpulan_berkas_uid',
        'pengumpulan_berkas_user_uid',
        'pengumpulan_berkas_permintaan_id',
        'pengumpulan_berkas_file',
        'pengumpulan_berkas_nama',
        'pengumpulan_berkas_mime',
        'pengumpulan_berkas_size',
        'pengumpulan_berkas_tanggal',
        'pengumpulan_berkas_status',
        'pengumpulan_berkas_verified_at',
        'pengumpulan_berkas_verified_by',
        'pengumpulan_berkas_keterangan',
        'pengumpulan_berkas_sumber',
        'pengumpulan_berkas_sumber_id',
        'pengumpulan_berkas_created_at',
        'pengumpulan_berkas_updated_at',
    ];

    protected $casts = [
        'pengumpulan_berkas_size' => 'integer',
        'pengumpulan_berkas_tanggal' => 'datetime',
        'pengumpulan_berkas_verified_at' => 'datetime',
        'pengumpulan_berkas_created_at' => 'datetime',
        'pengumpulan_berkas_updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            SamperinUser::class,
            'pengumpulan_berkas_user_uid',
            'user_uid'
        );
    }

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(
            SamperinPermintaanBerkas::class,
            'pengumpulan_berkas_permintaan_id',
            'permintaan_id'
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            SamperinUser::class,
            'pengumpulan_berkas_verified_by',
            'user_uid'
        );
    }
}