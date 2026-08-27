<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinUserRole extends Model
{
    protected $table = 'samperin_user_role';

    protected $primaryKey = 'user_role_id';

    public $timestamps = false;

    protected $fillable = ['user_role_uid', 'user_role_user_uid', 'user_role_role_uid'];
}