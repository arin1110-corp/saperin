<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinBidang extends Model
{
    protected $table = 'samperin_bidang';

    protected $primaryKey = 'bidang_id';

    public $timestamps = false;

    protected $fillable = ['bidang_uid', 'bidang_kode', 'bidang_nama', 'bidang_status'];

    protected $casts = [
        'bidang_id' => 'integer',
        'bidang_status' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(SamperinUser::class, 'user_bidang_id', 'bidang_id');
    }
}