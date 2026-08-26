<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinGolongan extends Model
{
    protected $table = 'samperin_golongan';

    protected $primaryKey = 'golongan_id';

    public $timestamps = false;

    protected $fillable = ['golongan_uid', 'golongan_kode', 'golongan_nama', 'golongan_status'];

    public function users()
    {
        return $this->hasMany(SamperinUser::class, 'user_golongan_id', 'golongan_id');
    }
}