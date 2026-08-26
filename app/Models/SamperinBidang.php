<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinBidang extends Model
{
    protected $table = 'samperin_bidang';

    protected $primaryKey = 'bidang_id';

    public $timestamps = false;

    protected $fillable = ['bidang_uid', 'bidang_kode', 'bidang_nama', 'bidang_parent_id', 'bidang_status'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'bidang_parent_id', 'bidang_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'bidang_parent_id', 'bidang_id');
    }

    public function users()
    {
        return $this->hasMany(SamperinUser::class, 'user_bidang_id', 'bidang_id');
    }
}