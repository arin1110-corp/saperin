<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SamperinPeraturanGaji extends Model
{
    protected $table = 'samperin_peraturan_gaji';

    protected $primaryKey = 'peraturan_gaji_id';

    public $timestamps = false;

    protected $fillable = ['peraturan_gaji_uid', 'peraturan_gaji_nama', 'peraturan_gaji_nomor', 'peraturan_gaji_tahun', 'peraturan_gaji_tanggal', 'peraturan_gaji_status', 'peraturan_gaji_created_at', 'peraturan_gaji_updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->peraturan_gaji_uid)) {
                $model->peraturan_gaji_uid = (string) Str::uuid();
            }

            if (empty($model->peraturan_gaji_created_at)) {
                $model->peraturan_gaji_created_at = now();
            }
        });

        static::updating(function ($model) {
            $model->peraturan_gaji_updated_at = now();
        });
    }

    public function golongan()
    {
        return $this->hasMany(SamperinPeraturanGajiGolongan::class, 'peraturan_gaji_id', 'peraturan_gaji_id');
    }

    public function batch()
    {
        return $this->hasMany(SamperinKgbBatch::class, 'kgb_batch_peraturan_gaji_id', 'peraturan_gaji_id');
    }
}