<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinEselon extends Model
{
    protected $table = 'samperin_eselon';

    protected $primaryKey = 'eselon_id';

    public $timestamps = false;

    protected $fillable = ['eselon_uid', 'eselon_kode', 'eselon_nama', 'eselon_status'];

    public function users()
    {
        return $this->hasMany(SamperinUser::class, 'user_eselon_id', 'eselon_id');
    }
}