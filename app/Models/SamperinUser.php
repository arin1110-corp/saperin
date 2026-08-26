<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinUser extends Model
{
    protected $table = 'samperin_user';

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['user_uid', 'user_nip', 'user_nik', 'user_email', 'user_password', 'user_name', 'user_status'];

    protected $hidden = ['user_password'];

    protected $casts = [
        'user_status' => 'integer',
    ];
}