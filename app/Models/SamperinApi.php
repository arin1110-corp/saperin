<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamperinApi extends Model
{
    protected $table = 'samperin_api';

    protected $primaryKey = 'api_id';

    public $incrementing = true;

    protected $keyType = 'int';

    const CREATED_AT = 'api_created_at';

    const UPDATED_AT = 'api_updated_at';

    protected $fillable = ['api_id', 'api_uid', 'api_kode', 'api_nama', 'api_url', 'api_token', 'api_status', 'api_keterangan'];

    protected $casts = [
        'api_id' => 'integer',
        'api_status' => 'boolean',
    ];
}