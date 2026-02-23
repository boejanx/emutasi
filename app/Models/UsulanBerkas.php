<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanBerkas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_usulan_berkas';
    protected $primaryKey = 'id_berkas';

    protected $fillable = [
        'id_usulan_detail',
        'id_dokumen',
        'path_dokumen',
        'status',
    ];

    public function detail()
    {
        return $this->belongsTo(UsulanDetail::class, 'id_usulan_detail', 'id_detail');
    }

    public function dokumen()
    {
        return $this->belongsTo(RefDokumen::class, 'id_dokumen', 'id_dokumen');
    }
}
