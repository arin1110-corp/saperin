<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinPengumpulanFoto extends Model
{
    protected $table = 'samperin_pengumpulan_foto';

    protected $primaryKey = 'pengumpulan_foto_id';

    public $timestamps = false;

    protected $fillable = ['pengumpulan_foto_uid', 'pengumpulan_foto_user_uid', 'pengumpulan_foto_file', 'pengumpulan_foto_nama', 'pengumpulan_foto_mime', 'pengumpulan_foto_size', 'pengumpulan_foto_tanggal', 'pengumpulan_foto_keterangan'];

    protected $casts = [
        'pengumpulan_foto_tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(SamperinUser::class, 'pengumpulan_foto_user_uid', 'user_uid');
    }
}