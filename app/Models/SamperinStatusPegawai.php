<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinStatusPegawai extends Model
{
    protected $table = 'samperin_status_pegawai';

    protected $primaryKey = 'status_pegawai_id';

    public $timestamps = false;

    protected $fillable = ['status_pegawai_uid', 'status_pegawai_kode', 'status_pegawai_nama', 'status_pegawai_status'];

    public function users()
    {
        return $this->hasMany(SamperinUser::class, 'user_status_id', 'status_pegawai_id');
    }
}