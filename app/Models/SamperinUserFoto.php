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

    const CREATED_AT = 'user_foto_created_at';

    const UPDATED_AT = 'user_foto_updated_at';

    protected $fillable = ['user_foto_id', 'user_foto_uid', 'user_foto_user_uid', 'user_foto_file', 'user_foto_nama', 'user_foto_mime', 'user_foto_size', 'user_foto_tanggal', 'user_foto_keterangan'];

    protected $casts = [
        'user_foto_id' => 'integer',
        'user_foto_size' => 'integer',
        'user_foto_tanggal' => 'date:Y-m-d',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SamperinUser::class, 'user_foto_user_uid', 'user_uid');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $file = trim((string) $this->user_foto_file);

        if ($file === '') {
            return null;
        }

        $driveFileId = null;

        // Google Drive:
        // https://drive.google.com/file/d/FILE_ID/view
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $file, $matches)) {
            $driveFileId = $matches[1];
        }

        // Google Drive:
        // https://drive.google.com/open?id=FILE_ID
        // https://drive.google.com/uc?id=FILE_ID
        if (!$driveFileId && preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $file, $matches)) {
            $driveFileId = $matches[1];
        }

        if ($driveFileId) {
            return 'https://drive.google.com/thumbnail?id=' . $driveFileId . '&sz=w300';
        }

        return $file;
    }
}