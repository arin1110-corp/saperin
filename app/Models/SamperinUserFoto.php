<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SamperinUserFoto extends Model
{
    protected $table = 'samperin_user_foto';

    protected $primaryKey = 'user_foto_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /*
    |--------------------------------------------------------------------------
    | TIMESTAMP
    |--------------------------------------------------------------------------
    */

    const CREATED_AT = 'user_foto_created_at';

    const UPDATED_AT = 'user_foto_updated_at';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = ['user_foto_id', 'user_foto_uid', 'user_foto_user_uid', 'user_foto_file', 'user_foto_nama', 'user_foto_mime', 'user_foto_size', 'user_foto_tanggal', 'user_foto_keterangan'];

    /*
    |--------------------------------------------------------------------------
    | CAST
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'user_foto_id' => 'integer',

        'user_foto_size' => 'integer',

        'user_foto_tanggal' => 'date:Y-m-d',
    ];

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    |
    | samperin_user_foto.user_foto_user_uid
    |                 ↓
    | samperin_user.user_uid
    |
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            SamperinUser::class,

            'user_foto_user_uid',

            'user_uid',
        );
    }
}