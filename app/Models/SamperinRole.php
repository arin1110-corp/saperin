<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SamperinRole extends Model
{
    protected $table = 'samperin_role';

    protected $primaryKey = 'role_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['role_id', 'role_uid', 'role_nama', 'role_slug', 'role_status'];

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    |
    | Relasi menggunakan UID.
    |
    */

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(SamperinUser::class, 'samperin_user_role', 'user_role_role_uid', 'user_role_user_uid', 'role_uid', 'user_uid');
    }
}