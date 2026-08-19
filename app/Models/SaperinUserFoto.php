<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaperinUserFoto extends Model
{
    protected $table = 'saperin_user_foto';


    protected $primaryKey = 'id';


    protected $fillable = [

        'user_id',

        'user_foto_nama',

        'user_foto_path',

    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(

            SaperinUser::class,

            'user_id',

            'id'

        );
    }
}