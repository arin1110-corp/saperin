<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SamperinEselon extends Model
{
    use HasFactory;

    protected $table = 'samperin_eselon';

    protected $primaryKey = 'eselon_id';

    public $timestamps = false;

    protected $fillable = ['eselon_uid', 'eselon_kode', 'eselon_nama', 'eselon_status'];

    protected $casts = [
        'eselon_id' => 'integer',
        'eselon_status' => 'integer',
        'eselon_created_at' => 'datetime',
        'eselon_updated_at' => 'datetime',
    ];
}