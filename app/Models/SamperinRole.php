<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinRole extends Model
{
    protected $table = 'samperin_role';

    protected $primaryKey = 'role_id';

    public $timestamps = false;

    protected $fillable = ['role_uid', 'role_nama', 'role_slug', 'role_deskripsi', 'role_status'];

    public function users()
    {
        return $this->belongsToMany(
            SamperinUser::class,

            'samperin_user_role',

            'user_role_role_uid',
            'user_role_user_uid',

            'role_uid',
            'user_uid',
        )->withPivot('user_role_uid');
    }
}