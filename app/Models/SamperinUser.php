<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SamperinUser extends Model
{
    protected $table = 'samperin_user';

    protected $primaryKey = 'user_id';

    /*
    |--------------------------------------------------------------------------
    | ID LAMA SADARIN TETAP
    |--------------------------------------------------------------------------
    */

    public $incrementing = false;

    protected $keyType = 'int';

    /*
    |--------------------------------------------------------------------------
    | TIMESTAMP
    |--------------------------------------------------------------------------
    */

    const CREATED_AT = 'user_created_at';

    const UPDATED_AT = 'user_updated_at';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'user_uid',

        /*
        | IDENTITAS
        */

        'user_nip',
        'user_nik',
        'user_nama',

        'user_gelardepan',
        'user_gelarbelakang',

        'user_tempatlahir',
        'user_tgllahir',
        'user_jk',

        /*
        | MASTER
        */

        'user_jabatan_id',
        'user_bidang_id',
        'user_golongan_id',
        'user_eselon_id',
        'user_pendidikan_id',
        'user_jenis_kerja_id',

        /*
        | DATA KEPEGAWAIAN
        */

        'user_tmt',
        'user_spmt',

        'user_npwp',
        'user_bpjs',
        'user_norek_bpd',

        'user_kelasjabatan',
        'user_jmltanggungan',

        /*
        | KONTAK
        */

        'user_email',
        'user_notelp',
        'user_alamat',

        'user_lokasikerja',
        'user_keterangan',

        /*
        | STATUS
        */

        'user_status',

        /*
        | LOGIN
        */

        'user_password',
    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = ['user_password'];

    /*
    |--------------------------------------------------------------------------
    | CAST
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'user_id' => 'integer',

        'user_tgllahir' => 'date:Y-m-d',

        'user_tmt' => 'date:Y-m-d',

        'user_spmt' => 'date:Y-m-d',

        'user_status' => 'integer',

        'user_jmltanggungan' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    |
    | samperin_user.user_uid
    |          ↓
    | samperin_user_role.user_role_user_uid
    |
    | samperin_role.role_uid
    |          ↓
    | samperin_user_role.user_role_role_uid
    |
    */

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            SamperinRole::class,

            'samperin_user_role',

            'user_role_user_uid',
            'user_role_role_uid',

            'user_uid',
            'role_uid',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FOTO
    |--------------------------------------------------------------------------
    |
    | User:
    | user_uid
    |
    | Foto:
    | user_foto_user_uid
    |
    */

    public function foto(): HasOne
    {
        return $this->hasOne(
            SamperinUserFoto::class,

            'user_foto_user_uid',

            'user_uid',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HAS ROLE
    |--------------------------------------------------------------------------
    */

    public function hasRole(string $role): bool
    {
        return $this->roles()

            ->where('role_slug', $role)

            ->where('role_status', 1)

            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | HAS ANY ROLE
    |--------------------------------------------------------------------------
    */

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()

            ->whereIn('role_slug', $roles)

            ->where('role_status', 1)

            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | AUTH PASSWORD
    |--------------------------------------------------------------------------
    */

    public function getAuthPassword()
    {
        return $this->user_password;
    }
}