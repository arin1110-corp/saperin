<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinUserRole extends Model
{
    protected $table = 'samperin_user_role';

    protected $primaryKey = 'user_role_id';

    public $timestamps = false;

    protected $fillable = ['user_role_uid', 'user_role_user_uid', 'user_role_role_uid'];

    public function user()
    {
        return $this->belongsTo(SamperinUser::class, 'user_role_user_uid', 'user_uid');
    }

    public function role()
    {
        return $this->belongsTo(SamperinRole::class, 'user_role_role_uid', 'role_uid');
    }
}