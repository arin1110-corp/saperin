<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SamperinPeraturanGajiGolongan extends Model
{
    protected $table = 'samperin_peraturan_gaji_golongan';

    protected $primaryKey = 'peraturan_gaji_golongan_id';

    public $timestamps = false;

    protected $fillable = ['peraturan_gaji_golongan_uid', 'peraturan_gaji_id', 'golongan_id', 'peraturan_gaji_gaji_lama', 'peraturan_gaji_gaji_baru', 'peraturan_gaji_golongan_status', 'peraturan_gaji_golongan_created_at', 'peraturan_gaji_golongan_updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->peraturan_gaji_golongan_uid)) {
                $model->peraturan_gaji_golongan_uid = (string) Str::uuid();
            }

            if (empty($model->peraturan_gaji_golongan_created_at)) {
                $model->peraturan_gaji_golongan_created_at = now();
            }
        });

        static::updating(function ($model) {
            $model->peraturan_gaji_golongan_updated_at = now();
        });
    }

    public function peraturan()
    {
        return $this->belongsTo(SamperinPeraturanGaji::class, 'peraturan_gaji_id', 'peraturan_gaji_id');
    }

    public function golongan()
    {
        return $this->belongsTo(SamperinGolongan::class, 'golongan_id', 'golongan_id');
    }
}