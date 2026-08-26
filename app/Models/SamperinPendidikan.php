<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinPendidikan extends Model
{
    protected $table = 'samperin_pendidikan';

    protected $primaryKey = 'pendidikan_id';

    public $timestamps = false;

    protected $fillable = ['pendidikan_uid', 'pendidikan_kode', 'pendidikan_nama', 'pendidikan_status'];

    public function users()
    {
        return $this->hasMany(SamperinUser::class, 'user_pendidikan_id', 'pendidikan_id');
    }
}