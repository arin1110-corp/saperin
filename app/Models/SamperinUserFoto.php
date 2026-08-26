<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinUserFoto extends Model
{
    protected $table = 'samperin_user_foto';

    protected $primaryKey = 'user_foto_id';

    public $timestamps = false;

    protected $fillable = ['user_foto_uid', 'user_foto_user_uid', 'user_foto_file', 'user_foto_nama', 'user_foto_mime', 'user_foto_size', 'user_foto_tanggal', 'user_foto_keterangan'];

    protected $casts = [
        'user_foto_tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(SamperinUser::class, 'user_foto_user_uid', 'user_uid');
    }
}