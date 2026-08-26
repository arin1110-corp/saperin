<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinJabatan extends Model
{
    protected $table = 'samperin_jabatan';

    protected $primaryKey = 'jabatan_id';

    public $timestamps = false;

    protected $fillable = ['jabatan_uid', 'jabatan_kode', 'jabatan_nama', 'jabatan_status'];

    public function users()
    {
        return $this->hasMany(SamperinUser::class, 'user_jabatan_id', 'jabatan_id');
    }
}