<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefDokumen extends Model
{
    protected $table = 'ref_dokumen';
    protected $primaryKey = 'id_dokumen';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = ['id_dokumen'];
}
