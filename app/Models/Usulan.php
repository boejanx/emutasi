<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Usulan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_usulan';
    protected $primaryKey = 'id_usulan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_usulan',
        'no_surat',
        'tanggal_surat',
        'perihal',
        'no_whatsapp',
        'nomor_sk',
        'path_sk',
        'id_user',
        'status',
        'disposisi',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(UsulanDetail::class, 'id_usulan', 'id_usulan');
    }

    public function pesans()
    {
        return $this->hasMany(UsulanPesan::class, 'id_usulan', 'id_usulan')->orderBy('created_at', 'asc');
    }

    public function logs()
    {
        return $this->hasMany(UsulanLog::class, 'id_usulan', 'id_usulan')->orderBy('created_at', 'asc');
    }

    public function siasnUnorJabatan()
    {
        return $this->hasOne(SiasnUnorJabatan::class, 'id_usulan', 'id_usulan');
    }
}
