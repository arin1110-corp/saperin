<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SamperinKgbBatch extends Model
{
    protected $table = 'samperin_kgb_batch';

    protected $primaryKey = 'kgb_batch_id';

    public $timestamps = false;

    protected $fillable = ['kgb_batch_uid', 'kgb_batch_nama', 'kgb_batch_peraturan_gaji_id', 'kgb_batch_pejabat_id', 'kgb_batch_tanggal', 'kgb_batch_oleh_pejabat', 'kgb_batch_mulai_berlaku', 'kgb_batch_nomor_format', 'kgb_batch_nomor_awal', 'kgb_batch_nomor_akhir', 'kgb_batch_status', 'kgb_batch_created_at', 'kgb_batch_updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kgb_batch_uid)) {
                $model->kgb_batch_uid = (string) Str::uuid();
            }

            if (empty($model->kgb_batch_created_at)) {
                $model->kgb_batch_created_at = now();
            }
        });

        static::updating(function ($model) {
            $model->kgb_batch_updated_at = now();
        });
    }

    public function peraturanGaji()
    {
        return $this->belongsTo(SamperinPeraturanGaji::class, 'kgb_batch_peraturan_gaji_id', 'peraturan_gaji_id');
    }

    public function pejabat()
    {
        return $this->belongsTo(SamperinUser::class, 'kgb_batch_pejabat_id', 'user_id');
    }

    public function kgb()
    {
        return $this->hasMany(SamperinKgb::class, 'kgb_batch_id', 'kgb_batch_id');
    }
}