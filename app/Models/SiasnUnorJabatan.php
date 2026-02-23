<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiasnUnorJabatan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_sync' => 'boolean',
        'sync_response' => 'array',
    ];

    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'id_usulan');
    }
}
