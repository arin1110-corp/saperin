<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinPengumpulanBerkas extends Model
{
    protected $table = 'samperin_pengumpulan_berkas';

    protected $primaryKey = 'pengumpulan_berkas_id';

    public $timestamps = false;

    protected $fillable = ['pengumpulan_berkas_uid', 'pengumpulan_berkas_user_uid', 'pengumpulan_berkas_jenis', 'pengumpulan_berkas_file', 'pengumpulan_berkas_nama', 'pengumpulan_berkas_mime', 'pengumpulan_berkas_size', 'pengumpulan_berkas_tanggal', 'pengumpulan_berkas_keterangan'];

    protected $casts = [
        'pengumpulan_berkas_tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(SamperinUser::class, 'pengumpulan_berkas_user_uid', 'user_uid');
    }
}