<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SamperinKgb extends Model
{
    protected $table = 'samperin_kgb';

    protected $primaryKey = 'kgb_id';

    public $timestamps = false;

    protected $fillable = ['kgb_uid', 'kgb_batch_id', 'kgb_user_id', 'kgb_golongan_id', 'kgb_nomor_surat', 'kgb_tanggal_surat', 'kgb_pejabat_id', 'kgb_masa_kerja_tahun', 'kgb_masa_kerja_bulan', 'kgb_mulai_berlaku', 'kgb_nomor_sk', 'kgb_tanggal_sk', 'kgb_status', 'kgb_created_at', 'kgb_updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kgb_uid)) {
                $model->kgb_uid = (string) Str::uuid();
            }

            if (empty($model->kgb_created_at)) {
                $model->kgb_created_at = now();
            }
        });

        static::updating(function ($model) {
            $model->kgb_updated_at = now();
        });
    }

    public function batch()
    {
        return $this->belongsTo(SamperinKgbBatch::class, 'kgb_batch_id', 'kgb_batch_id');
    }

    public function user()
    {
        return $this->belongsTo(SamperinUser::class, 'kgb_user_id', 'user_id');
    }

    public function golongan()
    {
        return $this->belongsTo(SamperinGolongan::class, 'kgb_golongan_id', 'golongan_id');
    }

    public function pejabat()
    {
        return $this->belongsTo(SamperinUser::class, 'kgb_pejabat_id', 'user_id');
    }
}