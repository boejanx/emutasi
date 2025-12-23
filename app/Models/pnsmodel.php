<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pnsmodel extends Model
{
    protected $table = 'tb_usul';
    protected $primaryKey = 'id_usulan';
    public $timestamps = true;

    protected $fillable = [
        'id_usulan',
        'nip',
        'nama',
        'jabatan',
        'mk_tahun',
        'mk_bulan',
        'unor_lama',
        'unor_baru',
        'status_usul',
        'no_wa',
        'email',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
