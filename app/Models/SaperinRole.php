<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SaperinRole extends Model
{
    protected $table = 'saperin_role';


    protected $primaryKey = 'id';


    protected $fillable = [

        'role_uid',

        'role_nama',

        'role_slug',

        'role_deskripsi',

        'role_status',

    ];


    protected function casts(): array
    {
        return [

            'role_status' => 'boolean',

        ];
    }


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(

            SaperinUser::class,

            'saperin_user_role',

            'role_id',

            'user_id'

        )->withTimestamps();
    }
}