<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SaperinUser extends Authenticatable
{
    use Notifiable;


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'saperin_user';


    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';


    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'user_uid',

        'user_nip',

        'user_nama',

        'user_email',

        'user_password',

        'user_status',

    ];


    /*
    |--------------------------------------------------------------------------
    | AUTH PASSWORD
    |--------------------------------------------------------------------------
    */

    protected $hidden = [

        'user_password',

        'remember_token',

    ];


    /*
    |--------------------------------------------------------------------------
    | PASSWORD FIELD
    |--------------------------------------------------------------------------
    */

    public function getAuthPassword()
    {
        return $this->user_password;
    }


    /*
    |--------------------------------------------------------------------------
    | CAST
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'user_status' => 'boolean',

            'user_password' => 'hashed',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(

            SaperinRole::class,

            'saperin_user_role',

            'user_id',

            'role_id'

        )->withTimestamps();
    }


    /*
    |--------------------------------------------------------------------------
    | FOTO
    |--------------------------------------------------------------------------
    */

    public function foto(): HasOne
    {
        return $this->hasOne(
            SaperinUserFoto::class,
            'user_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PEGAWAI
    |--------------------------------------------------------------------------
    */

    public function isPegawai(): bool
    {
        return $this->user_status === true;
    }


    /*
    |--------------------------------------------------------------------------
    | CEK ROLE
    |--------------------------------------------------------------------------
    */

    public function hasRole(
        string $role
    ): bool {

        return $this->roles()

            ->where(
                'role_slug',
                $role
            )

            ->where(
                'role_status',
                true
            )

            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | CEK SALAH SATU ROLE
    |--------------------------------------------------------------------------
    */

    public function hasAnyRole(
        array $roles
    ): bool {

        return $this->roles()

            ->whereIn(
                'role_slug',
                $roles
            )

            ->where(
                'role_status',
                true
            )

            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | CEK SEMUA ROLE
    |--------------------------------------------------------------------------
    */

    public function hasAllRoles(
        array $roles
    ): bool {

        return $this->roles()

            ->whereIn(
                'role_slug',
                $roles
            )

            ->where(
                'role_status',
                true
            )

            ->count() === count($roles);
    }
}